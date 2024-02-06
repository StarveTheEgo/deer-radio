<?php

declare(strict_types=1);

namespace App\Components\Google;

use App\Components\ComponentData\ComponentDataAccessor;
use App\Components\ComponentData\Entity\ComponentData;
use App\Components\ComponentData\Service\ComponentDataAccessService;
use App\Components\Output\Entity\Output;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveStream;

class GoogleDataAccessor extends ComponentDataAccessor
{
    /**
     * @param ComponentDataAccessService $service
     */
    public function __construct(ComponentDataAccessService $service)
    {
        parent::__construct($service, GoogleServiceProvider::COMPONENT_NAME);
    }

    /**
     * @param Output $output
     * @return LiveStream|null
     */
    public function getYoutubeLiveStream(Output $output): ?LiveStream
    {
        return $this->getValue('youtube-live-stream-'.$output->getId());
    }

    /**
     * @param Output $output
     * @param LiveStream|null $liveStream
     * @return ComponentData
     */
    public function setYoutubeLiveStream(Output $output, ?LiveStream $liveStream): ComponentData
    {
        return $this->setValue('youtube-live-stream-'.$output->getId(), $liveStream);
    }

    /**
     * @param Output $output
     * @return LiveBroadcast|null
     */
    public function getYoutubeLiveBroadcast(Output $output): ?LiveBroadcast
    {
        return $this->getValue('youtube-live-broadcast-'.$output->getId());
    }

    /**
     * @param Output $output
     * @param LiveBroadcast|null $liveBroadcast
     * @return ComponentData
     */
    public function setYoutubeLiveBroadcast(Output $output, ?LiveBroadcast $liveBroadcast): ComponentData
    {
        return $this->setValue('youtube-live-broadcast-'.$output->getId(), $liveBroadcast);
    }
}
