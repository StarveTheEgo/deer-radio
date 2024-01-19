<?php

declare(strict_types=1);

namespace App\Components\Output\Orchid\Screen;

use App\Components\OrchidIntergration\Helper\PrefixHelper;
use App\Components\Output\Entity\Output;
use App\Components\Output\Enum\OutputRoute;
use App\Components\Output\Filler\OutputFiller;
use App\Components\Output\Orchid\Enum\OutputPermission;
use App\Components\Output\Orchid\Layout\OutputEditLayout;
use App\Components\Output\Registry\OutputDriverRegistry;
use App\Components\Output\Service\OutputCreateService;
use App\Components\Output\Service\OutputReadService;
use App\Components\Output\Service\OutputUpdateService;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JsonException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layout;
use Orchid\Support\Facades\Toast;

class OutputEditScreen extends AbstractScreen
{
    public const QUERY_KEY_OUTPUT = 'output';

    private OutputDriverRegistry $driverRegistry;

    private OutputFiller $outputFiller;

    private OutputCreateService $createService;

    private OutputReadService $readService;

    private OutputUpdateService $updateService;

    /**
     * @return string|null
     */
    public static function getName(): ?string
    {
        return __('Manage outputs');
    }

    /**
     * @return string
     */
    public static function getRoute(): string
    {
        return OutputRoute::EDIT->value;
    }

    /**
     * @return array<string>|null
     */
    public static function getPermissions(): ?array
    {
        return [
            OutputPermission::CAN_EDIT->value,
        ];
    }

    /**
     * @param OutputDriverRegistry $driverRegistry
     * @param OutputFiller $outputFiller
     * @param OutputCreateService $createService
     * @param OutputReadService $readService
     * @param OutputUpdateService $updateService
     */
    public function __construct(
        OutputDriverRegistry $driverRegistry,
        OutputFiller $outputFiller,
        OutputCreateService $createService,
        OutputReadService $readService,
        OutputUpdateService $updateService
    ) {
        $this->driverRegistry = $driverRegistry;
        $this->outputFiller = $outputFiller;
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {

        return __('Livestream Output settings');
    }

    /**
     * @return iterable
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @param Output|null $output
     * @return iterable
     */
    public function query(?Output $output): iterable
    {
        return [
            self::QUERY_KEY_OUTPUT => $output,
        ];
    }

    /**
     * @return string[]|Layout[]
     */
    public function layout(): iterable
    {
        return [
            OutputEditLayout::class,
        ];
    }

    /**
     * @param array<string, mixed> $requestData
     * @return array[]
     */
    private function rules(array $requestData = []): array
    {
        return PrefixHelper::addPrefixToArrayKeys(self::QUERY_KEY_OUTPUT.'.',[
            'id' => [
                'string',
                'nullable',
            ],
            'outputName' => [
                'required',
                'string',
                Rule::unique(Output::class, 'outputName')
                    ->ignore($requestData[self::QUERY_KEY_OUTPUT]['id'] ?? null),
            ],
            'driverName' => [
                'required',
                'string',
                Rule::in(array_keys($this->driverRegistry->getDriverClasses()))
            ],
            'driverConfig' => [
                'string',
                'json',
            ],
            'isActive' => [
                'boolean',
            ],
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws JsonException|ValidationException
     */
    public function save(Request $request): RedirectResponse
    {
        $validatedData = $request->validate(
            $this->rules($request->all() ?? [])
        );

        $outputData = $validatedData['output'];
        $outputData['driverConfig'] = json_decode($outputData['driverConfig'], true, flags: JSON_THROW_ON_ERROR);

        $isNew = empty($outputData['id']);

        // create empty object or getting existing one
        if ($isNew) {
            $output = new Output();
        } else {
            $output = $this->readService->getById((int) $outputData['id']);

            // let's additionally check that driver is not changed
            if ($output->getDriverName() !== $outputData['driverName']) {
                throw ValidationException::withMessages(PrefixHelper::addPrefixToArrayKeys(self::QUERY_KEY_OUTPUT.'.', [
                   'driverName' => 'Driver cannot be changed. Please, create a new output',
                ]));
            }
        }

        $this->outputFiller->fillFromArray($output, $outputData);

        // creating or updating object in the storage via service
        if ($isNew) {
            $this->createService->create($output);
        } else {
            $this->updateService->update($output);
        }

        Toast::info(__('Output was saved.'));

        return redirect()->route(OutputRoute::INDEX->value);
    }
}
