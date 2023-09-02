<?php

namespace App\Console\Commands;

use App\DeerRadio\YoutubeApi;
use Illuminate\Console\Command;

class NotifyStreamChat extends Command {

    protected $signature = 'stream-chat:notify {message}';
    protected $description = 'Notifies livestream chat about selected subjects';
    /** @var YoutubeApi */
    private $youtubeChatManager;

    public function __construct() {
        parent::__construct();
        $this->youtubeChatManager = new YoutubeApi();
    }

    public function handle() {
        $is_enabled = (bool)get_radio_setting('deer-radio.chat_notification_enabled');
        if (!$is_enabled) {
            return;
        }
        $message_json = $this->argument('message');
        /*var_dump($message_json);
        $message = json_decode($message_json);
        if (!is_string($message)) {
            throw new \LogicException('Message must be a JSON-encoded string, got: '.var_export($message_json, true).' ('.gettype($message).')');
        }*/
        $message = $message_json;
//        $message = $message.$message.$message;
        // splitting message into chunks
        $limit = $this->youtubeChatManager->getMessageLengthLimit();
        $delimiter = ' ... ';
        $limit -= mb_strlen($limit);
        if ($limit > 0 && mb_strlen($message) > $limit) {
            $message = str_replace("\n", '. ', $message);
            $parts = array_filter(explode("\n", $this->utf8_wordwrap($message, $limit, "\n", PHP_EOL)));
        } else {
            $parts = [$message];
        }

        // sending message by parts
        $parts_count = count($parts);
        for ($i = 0; $i < $parts_count; $i++) {
            $message_part = $parts[$i];
            if ($i > 0) {
                $message_part = $delimiter.$message_part;
            } elseif ($i === 0 && $parts_count > 1) {
                $message_part .= $delimiter;
            }
            try {
                $this->youtubeChatManager->sendMessage($message_part);
            } catch (\Exception $ex) {
                throw new \Exception(sprintf('Error while sending "%s": %s', $message_part, $ex->getMessage()));
            }
        }
    }

    private function utf8_wordwrap($string, $width=75, $break="\n", $cut=false) {
        if($cut) {
            // Match anything 1 to $width chars long followed by whitespace or EOS,
            // otherwise match anything $width chars long
            $search = '/(.{1,'.$width.'})(?:\s|$)|(.{'.$width.'})/uS';
            $replace = '$1$2'.$break;
        } else {
            // Anchor the beginning of the pattern with a lookahead
            // to avoid crazy backtracking when words are longer than $width
            $search = '/(?=\s)(.{1,'.$width.'})(?:\s|$)/uS';
            $replace = '$1'.$break;
        }
        return preg_replace($search, $replace, $string);
    }
}
