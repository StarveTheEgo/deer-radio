<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Input\FieldOptions\InputOptions;
use App\Components\Setting\Entity\Setting;
use App\Components\UnsplashClient\Enum\UnsplashClientSettingKey;

class UnsplashClientSettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createAppIdSetting(),
            $this->createAppNameSetting(),
            $this->createAppSecretSetting(),
        ]);
    }

    private function createAppIdSetting() : Setting
    {
        return (new Setting())
            ->setKey(UnsplashClientSettingKey::APP_ID->value)
            ->setDescription('Unsplash app ID')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }

    private function createAppNameSetting() : Setting
    {
        return (new Setting())
            ->setKey(UnsplashClientSettingKey::APP_NAME->value)
            ->setDescription('Unsplash app name')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }

    private function createAppSecretSetting() : Setting
    {
        $fieldOptions = (new InputOptions())
            ->setValidation(null)
            ->setType('password');

        return (new Setting())
            ->setKey(UnsplashClientSettingKey::APP_SECRET->value)
            ->setDescription('Unsplash app secret')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(true);
    }
}
