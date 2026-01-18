<?php

declare(strict_types=1);

namespace App\Components\Setting;

use App\Components\Setting\Entity\Setting;
use LogicException;

final readonly class SettingNameInfo
{
    private const string GROUP_DELIMITER = '.';

    public static function fromSetting(Setting $setting): SettingNameInfo
    {
        $settingKey = $setting->getKey();
        if (1 !== substr_count($settingKey, self::GROUP_DELIMITER)) {
            throw new LogicException(sprintf('Setting "%s" must have grouped key format', $settingKey));
        }
        [$groupName, $shortName] = explode(self::GROUP_DELIMITER, $settingKey);

        return new self($groupName, $shortName);
    }

    public function __construct(private string $group, private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGroup(): string
    {
        return $this->group;
    }
}
