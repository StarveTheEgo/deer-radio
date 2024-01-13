<?php

declare(strict_types=1);

namespace App\Components\OrchidIntergration\Factory;

use Orchid\Screen\Fields\Code;

class JsonFieldFactory
{
    /**
     * @param string|null $name
     * @return Code
     */
    public static function make(?string $name = null) : Code
    {
        $field = Code::make($name)
            ->language(Code::JS); // vendor's implementation 'json' is broken, we so are using similar syntax highlighter here

        $field->addBeforeRender(function () use ($field) {
            $value = $field->get('value');

            if (!is_scalar($value) && $value !== null) {
                $value = json_encode($value, flags: JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            }

            $field->set('value', $value);
        });

        return $field;
    }
}
