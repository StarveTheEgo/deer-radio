<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\DeerRadio\UnsplashSearchQuery\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\ImageData\Driver\UnsplashDriver;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\Factory\Input\InputCustomOptions;
use App\Components\Setting\Orchid\Field\Factory\Toggle\ToggleCustomOptions;
use App\Components\Setting\Orchid\Field\FieldOptions;
use App\Components\Setting\Orchid\Field\FieldType;

class UnsplashQuerySettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createIsEnabledSetting(),
            $this->createDefaultSearchPromptSetting(),
            $this->createImageListCountSetting(),
            $this->createDownloadQueryParamsSetting(),
        ]);
    }

    private function createIsEnabledSetting(): Setting
    {
        $customFieldOptions = (new ToggleCustomOptions())
            ->setDescription('When enabled, Deer Radio will request and use images from Unsplash API');

        $fieldOptions = (new FieldOptions())
            ->setTitle('Enable Unsplash image feature')
            ->setValidation(null)
            ->setCustom($customFieldOptions->toArray());

        return (new Setting())
            ->setKey(UnsplashDriver::SETTING_IS_ENABLED)
            ->setDescription('Unsplash image feature')
            ->setValue('0')
            ->setFieldType((FieldType::TOGGLE)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }

    private function createDefaultSearchPromptSetting() : Setting
    {
        return (new Setting())
            ->setKey(DeerRadioUnsplashSearchQueryBuilder::SETTING_DEFAULT_SEARCH_PROMPT)
            ->setDescription('Default search prompt')
            ->setValue('deer')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(false);
    }

    private function createImageListCountSetting() : Setting
    {
        $customFieldOptions = (new InputCustomOptions())
            ->setType('number');

        $fieldOptions = (new FieldOptions())
            ->setValidation(null)
            ->setCustom($customFieldOptions->toArray());

        return (new Setting())
            ->setKey(DeerRadioUnsplashSearchQueryBuilder::SETTING_IMAGE_LIST_COUNT)
            ->setDescription('Amount of images per API request')
            ->setValue('30')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }

    private function createDownloadQueryParamsSetting(): Setting
    {
        $customFieldOptions = (new InputCustomOptions())
            ->setDescription('Additional image download query parameters, i.e. "&h=1080&q=100"');

        $fieldOptions = (new FieldOptions())
            ->setCustom($customFieldOptions->toArray());

        return (new Setting())
            ->setKey(UnsplashDriver::SETTING_DOWNLOAD_QUERY_PARAMS)
            ->setDescription('Additional download parameters')
            ->setValue('&h=1080&q=100')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }
}
