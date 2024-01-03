<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Screen;

use App\Components\Output\Entity\Output;
use App\Components\Output\Orchid\Enum\OutputPermission;
use App\Components\Output\Orchid\Enum\OutputRoute;
use App\Components\Output\Orchid\Layout\OutputListLayout;
use App\Components\Output\Service\OutputDeleteService;
use App\Components\Output\Service\OutputReadService;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Screens\IconAwareInterface;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;

class OutputIndexScreen extends AbstractScreen implements IconAwareInterface
{
    public const QUERY_KEY_OUTPUTS = 'outputs';

    private OutputReadService $readService;

    private OutputDeleteService $deleteService;

    public static function getName(): ?string
    {
        return __('Outputs');
    }

    public static function getIcon(): string
    {
        return 'feed';
    }

    public static function getRoute(): string
    {
        return OutputRoute::INDEX->value;
    }

    /**
     * @inheritDoc
     */
    public static function getPermissions(): ?array
    {
        return [
            OutputPermission::CAN_VIEW->value,
        ];
    }

    /**
     * @param OutputReadService $readService
     * @param OutputDeleteService $deleteService
     */
    public function __construct(
        OutputReadService $readService,
        OutputDeleteService $deleteService
    )
    {
        $this->readService = $readService;
        $this->deleteService = $deleteService;
    }

    public function description(): ?string
    {
        return __('Livestream outputs');
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add new output'))
                ->icon('plus')
                ->route(OutputRoute::CREATE->value),
        ];
    }

    public function query(): iterable
    {
        // @todo consider pagination
        return [
            self::QUERY_KEY_OUTPUTS => $this->readService->filteredFindAll($this->filters()),
        ];
    }

    public function layout(): iterable
    {
        return [
            OutputListLayout::class,
        ];
    }

    /**
     * @param Output $output
     */
    public function delete(Output $output): void
    {
        $this->deleteService->delete($output);

        Toast::info(__('Output was removed'));
    }
}
