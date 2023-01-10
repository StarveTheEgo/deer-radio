<?php

declare(strict_types=1);

namespace App\Components\Setting\Service;

use App\Components\Setting\Entity\Setting;
use Illuminate\Encryption\Encrypter;

class SettingValueService
{
    private Encrypter $encrypter;

    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    public function setValue(Setting $setting, ?string $value): void
    {
        if (null !== $value && $setting->isEncrypted()) {
            $value = $this->encrypter->encryptString($value);
        }
        $setting->setValue($value);
    }

    public function getValue(Setting $setting): ?string
    {
        $value = $setting->getValue();
        if (null !== $value && $setting->isEncrypted()) {
            $value = $this->encrypter->decryptString($value);
        }

        return $value;
    }
}
