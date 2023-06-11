<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\DeerRadio\DeerRadioUnsplashSearchQueryBuilder;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\Factory\Input\InputCustomOptions;
use App\Components\Setting\Orchid\Field\FieldOptions;
use App\Components\Setting\Orchid\Field\FieldType;

class UnsplashQuerySettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createDefaultSearchPromptSetting(),
            $this->createImageListCountSetting(),
        ]);
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
}
