<?php

declare(strict_types=1);

namespace App\Components\Author\Service;

class AuthorServiceRegistry
{
    private AuthorCreateService $createService;
    private AuthorReadService $readService;
    private AuthorUpdateService $updateService;
    private AuthorDeleteService $deleteService;

    public function __construct(
        AuthorCreateService $createService,
        AuthorReadService $readService,
        AuthorUpdateService $updateService,
        AuthorDeleteService $deleteService
    )
    {
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
    }

    /**
     * @return AuthorCreateService
     */
    public function getCreateService(): AuthorCreateService
    {
        return $this->createService;
    }

    /**
     * @return AuthorReadService
     */
    public function getReadService(): AuthorReadService
    {
        return $this->readService;
    }

    /**
     * @return AuthorUpdateService
     */
    public function getUpdateService(): AuthorUpdateService
    {
        return $this->updateService;
    }

    /**
     * @return AuthorDeleteService
     */
    public function getDeleteService(): AuthorDeleteService
    {
        return $this->deleteService;
    }
}
