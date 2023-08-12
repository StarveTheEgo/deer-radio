<?php

declare(strict_types=1);

namespace App\Components\Setting\Service;

class SettingServiceRegistry
{
    private SettingCreateService $createService;
    private SettingReadService $readService;
    private SettingUpdateService $updateService;
    private SettingDeleteService $deleteService;
    private SettingValueService $valueService;

    public function __construct(
        SettingCreateService $createService,
        SettingReadService $readService,
        SettingUpdateService $updateService,
        SettingDeleteService $deleteService,
        SettingValueService $settingValueService
    )
    {
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
        $this->valueService = $settingValueService;
    }

    /**
     * @return SettingCreateService
     */
    public function getCreateService(): SettingCreateService
    {
        return $this->createService;
    }

    /**
     * @return SettingReadService
     */
    public function getReadService(): SettingReadService
    {
        return $this->readService;
    }

    /**
     * @return SettingUpdateService
     */
    public function getUpdateService(): SettingUpdateService
    {
        return $this->updateService;
    }

    /**
     * @return SettingDeleteService
     */
    public function getDeleteService(): SettingDeleteService
    {
        return $this->deleteService;
    }

    /**
     * @return SettingValueService
     */
    public function getValueService(): SettingValueService
    {
        return $this->valueService;
    }
}
