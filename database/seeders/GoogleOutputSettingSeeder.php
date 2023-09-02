<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Components\Google\Enum\GoogleOutputSettingKey;
use App\Components\OrchidIntergration\Field\Factory\Code\CodeCustomOptions;
use App\Components\OrchidIntergration\Field\Factory\Input\InputCustomOptions;
use App\Components\OrchidIntergration\Field\FieldOptions;
use App\Components\OrchidIntergration\Field\FieldType;
use App\Components\Setting\Entity\Setting;
use Orchid\Screen\Fields\Code;

class GoogleOutputSettingSeeder extends AbstractSettingSeeder
{
    public function run(): void
    {
        $this->createNotExistingSettings([
            $this->createEndpointSetting(),
            $this->createApiKeySetting(),
            $this->createAccessTokenSetting(),
            $this->createRefreshTokenSetting(),
            $this->createAuthConfigSetting(),
        ]);
    }

    private function createEndpointSetting() : Setting
    {
        return (new Setting())
            ->setKey(GoogleOutputSettingKey::ENDPOINT->value)
            ->setDescription('Endpoint')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }

    private function createApiKeySetting() : Setting
    {
        return (new Setting())
            ->setKey(GoogleOutputSettingKey::API_KEY->value)
            ->setDescription('API key')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }

    private function createAccessTokenSetting(): Setting
    {
        $customFieldOptions = (new InputCustomOptions())
            ->setDescription('This access token will be updated automatically after expiration');

        $fieldOptions = (new FieldOptions())
            ->setValidation(null)
            ->setCustom($customFieldOptions->toArray());

        return (new Setting())
            ->setKey(GoogleOutputSettingKey::ACCESS_TOKEN->value)
            ->setDescription('Access token')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(true);
    }

    private function createRefreshTokenSetting(): Setting
    {
        return (new Setting())
            ->setKey(GoogleOutputSettingKey::REFRESH_TOKEN->value)
            ->setDescription('Refresh token')
            ->setValue(null)
            ->setFieldType((FieldType::INPUT)->value)
            ->setFieldOptions(null)
            ->setIsEncrypted(true);
    }

    private function createAuthConfigSetting(): Setting
    {
        $customFieldOptions = (new CodeCustomOptions())
            ->setLanguage(Code::JS);

        $fieldOptions = (new FieldOptions())
            ->setValidation([
                'json',
            ])
            ->setCustom($customFieldOptions->toArray());

        return (new Setting())
            ->setKey(GoogleOutputSettingKey::AUTH_CONFIG->value)
            ->setDescription('Authentication config')
            ->setValue(null)
            ->setFieldType((FieldType::CODE)->value)
            ->setFieldOptions($fieldOptions->toArray())
            ->setIsEncrypted(true);
    }
}
