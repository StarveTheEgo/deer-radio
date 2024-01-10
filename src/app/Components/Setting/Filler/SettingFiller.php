<?php

declare(strict_types=1);

namespace App\Components\Setting\Filler;

use App\Components\Setting\Entity\Setting;

class SettingFiller
{
    /**
     * Fills setting object from input data
     * @param Setting $setting
     * @param array<string, mixed> $input
     * @return Setting
     */
    public function fillFromArray(Setting $setting, array $input) : Setting
    {
        $setting->setKey($input['key']);
        $setting->setDescription($input['description']);
        $setting->setFieldType($input['fieldType']);
        $setting->setFieldOptions($input['fieldOptions']);
        $setting->setIsEncrypted((bool) $input['isEncrypted']);
        $setting->setOrd(0); // @todo sorting

        return $setting;
    }
}
