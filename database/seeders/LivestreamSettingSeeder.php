<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\DeerRadio\Enum\LivestreamSettingKey;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\FieldType;

class LivestreamSettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createEndpointSetting(),
            $this->createApiKeySetting(),
        ]);
    }

    private function createEndpointSetting() : Setting
    {
        return (new Setting())
            ->setKey(LivestreamSettingKey::ENDPOINT->value)
            ->setDescription('Endpoint')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }

    private function createApiKeySetting() : Setting
    {
        return (new Setting())
            ->setKey(LivestreamSettingKey::API_KEY->value)
            ->setDescription('API key')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }
}
