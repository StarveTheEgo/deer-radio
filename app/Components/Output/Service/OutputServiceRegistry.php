<?php

declare(strict_types=1);

namespace App\Components\Output\Service;

class OutputServiceRegistry
{
    private OutputCreateService $createService;
    private OutputReadService $readService;
    private OutputUpdateService $updateService;
    private OutputDeleteService $deleteService;

    public function __construct(
        OutputCreateService $createService,
        OutputReadService $readService,
        OutputUpdateService $updateService,
        OutputDeleteService $deleteService
    )
    {
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
    }

    /**
     * @return OutputCreateService
     */
    public function getCreateService(): OutputCreateService
    {
        return $this->createService;
    }

    /**
     * @return OutputReadService
     */
    public function getReadService(): OutputReadService
    {
        return $this->readService;
    }

    /**
     * @return OutputUpdateService
     */
    public function getUpdateService(): OutputUpdateService
    {
        return $this->updateService;
    }

    /**
     * @return OutputDeleteService
     */
    public function getDeleteService(): OutputDeleteService
    {
        return $this->deleteService;
    }
}
