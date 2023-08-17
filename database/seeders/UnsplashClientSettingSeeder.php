<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\Factory\Input\InputCustomOptions;
use App\Components\Setting\Orchid\Field\FieldOptions;
use App\Components\Setting\Orchid\Field\FieldType;
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
        $customFieldOptions = (new InputCustomOptions())
            ->setType('password');

        $fieldOptions = (new FieldOptions())
            ->setValidation(null)
            ->setCustom($customFieldOptions->toArray());

        return (new Setting())
            ->setKey(UnsplashClientSettingKey::APP_SECRET->value)
            ->setDescription('Unsplash app secret')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(true);
    }
}
