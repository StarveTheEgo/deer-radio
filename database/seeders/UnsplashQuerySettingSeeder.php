<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\DeerRadio\Enum\UnsplashSearchQueryBuilderSettingKey;
use App\Components\ImageData\Enum\UnsplashDriverSettingKey;
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
            ->setKey(UnsplashDriverSettingKey::IS_ENABLED->value)
            ->setDescription('Unsplash image feature')
            ->setValue('0')
            ->setFieldType((FieldType::TOGGLE)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }

    private function createDefaultSearchPromptSetting() : Setting
    {
        return (new Setting())
            ->setKey(UnsplashSearchQueryBuilderSettingKey::DEFAULT_SEARCH_PROMPT->value)
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
            ->setKey(UnsplashSearchQueryBuilderSettingKey::IMAGE_LIST_COUNT->value)
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
            ->setKey(UnsplashDriverSettingKey::DOWNLOAD_QUERY_PARAMS->value)
            ->setDescription('Additional download parameters')
            ->setValue('&h=1080&q=100')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }
}
