<?php

declare(strict_types=1);

namespace App\Components\Setting\Repository;

use App\Components\DoctrineOrchid\Repository\AbstractRepository;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Orchid\Field\FieldOptions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use LogicException;

class SettingRepository extends AbstractRepository implements SettingRepositoryInterface
{
    public function findByKey(string $key): ?Setting
    {
        /** @var Setting|null $setting */
        $setting = $this->getEntityRepository()->findOneBy(['key' => $key]);
        return $setting;
    }

    public function create(Setting $setting): void
    {
        $this->validateSettingValue($setting);

        parent::createObject($setting);
    }

    public function update(Setting $setting): void
    {
        $this->validateSettingValue($setting);

        parent::updateObject($setting);
    }

    public function delete(Setting $setting): void
    {
        $em = $this->getEntityManager();
        if (!$em->contains($setting)) {
            throw new LogicException("Setting '{$setting->getKey()}' is not persisted");
        }
        $em->remove($setting);
        $em->flush();
    }

    private function validateSettingValue(Setting $setting): void
    {
        // @todo refactor
        $fieldOptions = FieldOptions::fromArray($setting->getFieldOptions() ?? []);
        $validation = $fieldOptions->getValidation();
        if (empty($validation)) {
            return;
        }

        // @fixme dependency injection
        $validator = Validator::make([
            'value' => $setting->getValue(),
        ], [
            'value' => $validation['rules'] ?? [],
        ], [
            'messages' => $validation['messages'] ?? [],
        ]);
        $validator->validate();
        if (!$validator->valid()) {
            throw new ValidationException($validator);
        }
    }
}
