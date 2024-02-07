<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\DeerRadio\Enum\UnsplashSearchQueryBuilderSettingKey;
use App\Components\ImageData\Enum\UnsplashDriverSettingKey;
use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Input\FieldOptions\InputOptions;
use App\Components\OrchidIntergration\Field\Toggle\FieldOptions\ToggleOptions;
use App\Components\Setting\Entity\Setting;

class UnsplashQuerySettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createIsEnabledSetting(),
            $this->createDefaultSearchPromptSetting(),
            $this->createImageListCountSetting(),
            $this->createDownloadQueryParamsSetting(),
            $this->createCustomPromptEnabledSetting(),
        ]);
    }

    private function createIsEnabledSetting(): Setting
    {
        $fieldOptions = (new ToggleOptions())
            ->setTitle('Enable Unsplash image feature')
            ->setDescription('When enabled, Deer Radio will request and use images from Unsplash API')
            ->setValidation(null);

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
        $fieldOptions = (new InputOptions())
            ->setValidation(null)
            ->setType('number');

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
        $fieldOptions = (new InputOptions())
            ->setDescription('Additional image download query parameters, i.e. "&h=1080&q=100"');

        return (new Setting())
            ->setKey(UnsplashDriverSettingKey::DOWNLOAD_QUERY_PARAMS->value)
            ->setDescription('Additional download parameters')
            ->setValue('&h=1080&q=100')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }

    private function createCustomPromptEnabledSetting(): Setting
    {
        $fieldOptions = (new ToggleOptions())
            ->setTitle('Enable custom Unsplash image prompt')
            ->setDescription('When enabled, Deer Radio will use custom search prompt for authors and tracks')
            ->setValidation(null);

        return (new Setting())
            ->setKey(UnsplashSearchQueryBuilderSettingKey::CUSTOM_PROMPT_ENABLED->value)
            ->setDescription('Custom query prompt')
            ->setValue('1')
            ->setFieldType((FieldType::TOGGLE)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }
}
