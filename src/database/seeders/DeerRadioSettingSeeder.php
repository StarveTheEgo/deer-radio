<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\DeerRadio\Enum\DeerRadioSettingKey;
use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\Setting\Entity\Setting;

class DeerRadioSettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createTitleSetting(),
            $this->createDescriptionSetting(),
        ]);
    }

    private function createTitleSetting() : Setting
    {
        return (new Setting())
            ->setKey(DeerRadioSettingKey::TITLE->value)
            ->setDescription('Deer Radio livestream title')
            ->setValue('Deer Radio stream 24/7')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(false);
    }

    private function createDescriptionSetting() : Setting
    {
        return (new Setting())
            ->setKey(DeerRadioSettingKey::DESCRIPTION->value)
            ->setDescription('Deer Radio description')
            ->setValue('Deer Radio stream')
            ->setFieldType((FieldType::TEXTAREA)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(false);
    }
}
