<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\ImageData\Enum\LocalImageSettingKey;
use App\Components\Setting\Entity\Setting;
use App\Components\OrchidIntergration\Field\FieldType;

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
            ->setKey(LocalImageSettingKey::IMAGE_PATHS->value)
            ->setDescription('Local fallback image paths (raw json for a while)')
            ->setValue('[]')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(false);
    }
}
