<?php

namespace App\Console\Commands;

use App\DeerRadio\YoutubeApi;
use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Google_Service_YouTube_LiveBroadcast;
use Google_Service_YouTube_LiveBroadcastContentDetails;
use Google_Service_YouTube_LiveBroadcastSnippet;
use Google_Service_YouTube_LiveBroadcastStatus;
use Google_Service_YouTube_LiveStream;
use Google_Service_YouTube_CdnSettings;
use Google_Service_YouTube_LiveStreamSnippet;
use LogicException;
use Exception;

class PrepareStream extends Command {

    use WithoutOverlapping;

    protected $signature = 'stream:prepare';
    protected $description = 'Initialization of the stream';
    /** @var YoutubeApi */
    private $youtubeApi;

    public function __construct() {
        parent::__construct();
        $this->youtubeApi = new YoutubeApi();
    }

    public function handle() {
        try {
            Artisan::call('cache:clear');
            $broadcast = $this->ensureExistingBroadcast();
            if ($broadcast === null) {
                throw new LogicException('Could not ensure existence of broadcast to stream');
            }
            // get livestream for this broadcast
            $stream = $this->ensureExistingLivestream($broadcast->snippet->title);
            // bind broadcast to a livestream
            try {
                $this->youtubeApi->getYoutubeServicePublic()->liveBroadcasts->bind($broadcast->id, 'id,contentDetails', [
                    'streamId' => $stream['id'],
                ]);
            } catch (Exception $ex) {
                if (strpos($ex->getMessage(), 'The binding is not allowed') !== false) {
                    Log::error('Could not bind, but probably is already bound');
                } else {
                    throw $ex;
                }
            }
            
            // store broadcast in the cache
            $this->youtubeApi->storeLiveBroadcast($broadcast);

            $settings = [
                'livestream' => [
                    'url' => $stream->cdn->ingestionInfo->ingestionAddress.'/'.$stream->cdn->ingestionInfo->streamName,
                    'message' => 'Run like a deer, Deer Radio!',
                ]
            ];
            $settings_json = json_encode($settings);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Could not encode radio settings to JSON');
            }
            $this->line($settings_json);
        } catch (Exception $ex) {
            Log::error('Could not prepare the stream: '.$ex->getMessage());
            throw $ex;
        }
    }

    private function ensureExistingLivestream() : Google_Service_YouTube_LiveStream {
        $youtube_service = $this->youtubeApi->getYoutubeServicePublic();
        $livestreams = $this->youtubeApi->getDeerRadioLivestreams();
        foreach ($livestreams as $livestream) {
            if ($livestream->status->streamStatus === 'active') {
                Log::error('Found active livestream');
                return $livestream;
            } else {
                try {
                    $youtube_service->liveStreams->delete($livestream->id);
                } catch (\Throwable $throwable) {
                    Log::error('Could not delete livestream: '.$throwable->getMessage().var_export($livestream, true));
                }
            }
        }

        Log::error('Creating new livestream');
        $livestream = $this->setUpLivestream(new Google_Service_YouTube_LiveStream());
        return $youtube_service->liveStreams->insert('snippet,cdn', $livestream);
    }

    private function setUpLivestream(Google_Service_YouTube_LiveStream $livestream) : Google_Service_YouTube_LiveStream {
        if ($livestream->snippet === null) {
            $snippet = new Google_Service_YouTube_LiveStreamSnippet();
            $livestream->setSnippet($snippet);
        } else {
            $snippet = $livestream->snippet;
        }
        $snippet->setTitle($this->youtubeApi->getStreamTitle());


        if ($livestream->cdn === null) {
            $cdn = new Google_Service_YouTube_CdnSettings();
            $livestream->setCdn($cdn);
        } else {
            $cdn = $livestream->cdn;
        }
        $cdn->setResolution('variable');
        $cdn->setFrameRate('variable');
        $cdn->setIngestionType('rtmp');
        $livestream->setKind('youtube#liveStream');
        return $livestream;
    }

    private function ensureExistingBroadcast() : ?Google_Service_YouTube_LiveBroadcast {
        $current_timestamp = time();

        // lets check if we have live broadcast
        $live_broadcast = $this->youtubeApi->getLiveBroadcast();
        if ($live_broadcast !== null) {
            Log::error('Found live broadcast');
            return $live_broadcast;
        }

        $youtube_service = $this->youtubeApi->getYoutubeServicePublic();
        // there is not any live broadcast now; lets check if there are any upcoming ones
        $upcoming_broadcasts = $this->youtubeApi->getDeerRadioBroadcasts('upcoming');
        if (!empty($upcoming_broadcasts)) {
            // foreach ($upcoming_broadcasts as $broadcast) {
            //     $youtube_service->liveBroadcasts->delete($broadcast->id);
            // }
            // lets find the earliest one
            $cmd_instance = $this;
            if (count($upcoming_broadcasts) > 1) {
                $earliest_broadcast = array_reduce($upcoming_broadcasts, function ($a, $b) use ($cmd_instance) {
                    $a_time = $cmd_instance->parseAtomString($a->snippet->scheduledStartTime);
                    $b_time = $cmd_instance->parseAtomString($b->snippet->scheduledStartTime);
                    return $a_time < $b_time ? $a : $b;
                });
            } else {
                $earliest_broadcast = $upcoming_broadcasts[array_key_first($upcoming_broadcasts)];
            }
            Log::error('Using earliest upcoming broadcast');
            // $earliest_broadcast = $this->setUpBroadcast($earliest_broadcast, ['snippet', 'status']);
            // try {
            //     $youtube_service->liveBroadcasts->update('snippet,status', $earliest_broadcast);
            // } catch (Exception $ex) {
            //     Log::error('Could not update earliest broadcast data');
            // }
            
            return $earliest_broadcast;
        }

        Log::error('Created new broadcast');
        // no chance to use any existing broadcast, should schedule the new one
        $new_broadcast = $this->setUpBroadcast(new Google_Service_YouTube_LiveBroadcast());
        return $youtube_service->liveBroadcasts->insert('snippet,contentDetails,status', $new_broadcast);
    }

    private function setUpBroadcast(Google_Service_YouTube_LiveBroadcast $broadcast, array $contents_filter = ['contentDetails', 'snippet', 'status']) : Google_Service_YouTube_LiveBroadcast {
        $contents_filter = array_flip($contents_filter);

        if (array_key_exists('contentDetails', $contents_filter)) {
            if ($broadcast->contentDetails === null) {
                $content_details = new Google_Service_YouTube_LiveBroadcastContentDetails();
                $broadcast->contentDetails = $content_details;
            } else {
                $content_details = $broadcast->contentDetails;
            }
            $content_details->setEnableLowLatency(true);
            $content_details->setEnableAutoStart(true);
            $content_details->setEnableAutoStop(true); // false
            $content_details->setEnableEmbed(false);
        }

        if (array_key_exists('snippet', $contents_filter)) {
            if ($broadcast->snippet === null) {
                $snippet = new Google_Service_YouTube_LiveBroadcastSnippet();
                $broadcast->setSnippet($snippet);
            } else {
                $snippet = $broadcast->snippet;
            }
            
            $start_time = $this->makeAtomString(time() - 10);
            $snippet->setScheduledStartTime($start_time);
    
            $snippet->setTitle($this->youtubeApi->getStreamTitle());
            $description = get_radio_setting('deer-radio.stream_description', '');
            if (!empty($description)) {
                $snippet->setDescription($description);
            }
        }

        if (array_key_exists('status', $contents_filter)) {
            if ($broadcast->status === null) {
                $status = new Google_Service_YouTube_LiveBroadcastStatus();
                $broadcast->setStatus($status);
            } else {
                $status = $broadcast->status;
            }
            $status->setPrivacyStatus('public');
        }
        return $broadcast;
    }

    private function parseAtomString(string $atom_datetime) : string {
        return date('U', strtotime($atom_datetime));
    }

    private function makeAtomString(string $timestamp) : string {
        return date('c', $timestamp);
    }
}
