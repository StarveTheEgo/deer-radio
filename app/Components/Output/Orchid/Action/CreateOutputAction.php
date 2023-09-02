<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Action;

use Illuminate\Support\Collection;
use Orchid\Crud\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Toast;

class CreateOutputAction extends Action
{
    private string $modalLayoutName;

    public function __construct(string $modalLayoutName)
    {
        $this->modalLayoutName = $modalLayoutName;
    }

    public function button(): Button
    {
        return ModalToggle::make('Launch demo modal')
            ->modal($this->modalLayoutName)
            ->method('create')
            ->icon('full-screen');
    }

    public function handle(Collection $models)
    {
        $models->each(function () {

        });

        Toast::message('It worked!');
    }
}
