<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\Liquidsoap\Enum\LiquidsoapSettingKey;
use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Field\Input\FieldOptions\InputOptions;
use App\Components\Setting\Entity\Setting;

class LiquidsoapSettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createMaxInactiveStreamDurationSetting(),
        ]);
    }

    private function createMaxInactiveStreamDurationSetting() : Setting
    {
        $fieldOptions = (new InputOptions())
            ->setValidation([
                'integer',
                'min:0',
            ])
            ->setType('number');

        return (new Setting())
            ->setKey(LiquidsoapSettingKey::MAX_INACTIVE_STREAM_DURATION->value)
            ->setDescription('Consider output unhealthy, if the stream did not start after X seconds after the last preparation')
            ->setValue('60')
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(false);
    }
}
