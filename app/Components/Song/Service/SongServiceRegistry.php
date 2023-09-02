<?php

declare(strict_types=1);

namespace App\Components\Song\Service;

class SongServiceRegistry
{
    private SongCreateService $createService;
    private SongReadService $readService;
    private SongUpdateService $updateService;
    private SongDeleteService $deleteService;

    public function __construct(
        SongCreateService $createService,
        SongReadService $readService,
        SongUpdateService $updateService,
        SongDeleteService $deleteService
    )
    {
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
    }

    /**
     * @return SongCreateService
     */
    public function getCreateService(): SongCreateService
    {
        return $this->createService;
    }

    /**
     * @return SongReadService
     */
    public function getReadService(): SongReadService
    {
        return $this->readService;
    }

    /**
     * @return SongUpdateService
     */
    public function getUpdateService(): SongUpdateService
    {
        return $this->updateService;
    }

    /**
     * @return SongDeleteService
     */
    public function getDeleteService(): SongDeleteService
    {
        return $this->deleteService;
    }
}
