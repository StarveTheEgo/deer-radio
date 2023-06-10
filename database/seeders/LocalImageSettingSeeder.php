<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\ImageData\ImageDataServiceProvider;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\FieldType;

class LocalImageSettingSeeder extends AbstractSettingSeeder
{
    public function run() : void
    {
        $this->createNotExistingSettings([
            $this->createImagePathsSetting(),
        ]);
    }

    private function createImagePathsSetting() : Setting
    {
        return (new Setting())
            ->setKey(ImageDataServiceProvider::SETTING_IMAGE_PATHS)
            ->setDescription('Local fallback image paths (raw json for a while)')
            ->setValue('[]')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(false);
    }
}
