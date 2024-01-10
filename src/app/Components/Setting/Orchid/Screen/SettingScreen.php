<?php

declare(strict_types=1);

namespace App\Components\Setting\Orchid\Screen;

use App\Components\OrchidIntergration\Enum\FieldType;
use App\Components\OrchidIntergration\Registry\FieldFactoryRegistry;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Filler\SettingFiller;
use App\Components\Setting\Orchid\Layout\SettingEditLayout;
use App\Components\Setting\Service\SettingCreateService;
use App\Components\Setting\Service\SettingDeleteService;
use App\Components\Setting\Service\SettingReadService;
use App\Components\Setting\Service\SettingUpdateService;
use App\Components\Setting\Service\SettingValueService;
use App\Components\Setting\SettingNameInfo;
use App\Components\Setting\SettingServiceProvider;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Screens\IconAwareInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Tabs;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingScreen extends AbstractScreen implements IconAwareInterface
{
    private SettingFiller $settingFiller;

    private FieldFactoryRegistry $fieldFactoryRegistry;

    private const MODAL_CREATE_SETTING = 'createSettingModal';

    private ?array $cachedQuery = null;
    private SettingCreateService $createService;
    private SettingReadService $readService;
    private SettingUpdateService $updateService;
    private SettingDeleteService $deleteService;
    private SettingValueService $valueService;

    public static function getName(): ?string
    {
        return __('Settings');
    }

    public static function getIcon(): string
    {
        return 'settings';
    }

    public static function getRoute(): string
    {
        return 'platform.app.settings';
    }

    public static function getPermissions(): ?array
    {
        return [
            'platform.app.settings',
        ];
    }

    public function __construct(
        SettingFiller $settingFiller,
        FieldFactoryRegistry $fieldFactoryRegistry,
        SettingValueService $valueService,
        SettingCreateService $createService,
        SettingReadService $readService,
        SettingUpdateService $updateService,
        SettingDeleteService $deleteService
    ) {
        $this->settingFiller = $settingFiller;
        $this->fieldFactoryRegistry = $fieldFactoryRegistry;
        $this->valueService = $valueService;
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
    }

    public function description(): ?string
    {
        return __('All the settings for the Deer Radio in one place');
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make(__('Create new setting'))
                ->modal(self::MODAL_CREATE_SETTING)
                ->modalTitle(__('Create new setting'))
                ->method('create')
                ->icon('plus'),

            Button::make(__('Save all'))
                ->icon('check')
                ->method('save'),
        ];
    }

    public function query(): iterable
    {
        if (null === $this->cachedQuery) {
            $settings = $this->readService->filteredFindAll($this->filters());
            $groupedSettings = [];
            foreach ($settings as $setting) {
                $nameInfo = SettingNameInfo::fromSetting($setting);
                $groupedSettings[$nameInfo->getGroup()][$nameInfo->getName()] = $setting;
            }
            $this->cachedQuery = [
                'groupedSettings' => $groupedSettings,
            ];
        }

        return $this->cachedQuery;
    }

    public function layout(): iterable
    {
        $groupedSettings = $this->query()['groupedSettings'];

        return [
            $this->buildTabsLayout($groupedSettings),
            $this->buildCreateSettingModal(),
        ];
    }

    /**
     * @param Setting[][] $groupedSettings
     */
    public function buildTabsLayout(array $groupedSettings): Tabs
    {
        $tabLayouts = [];
        foreach ($groupedSettings as $group => $settings) {
            $settingEditors = [];
            foreach ($settings as $setting) {
                $viewName = sprintf('%s::setting-editor', SettingServiceProvider::SERVICE_NS);
                $settingEditors[] = Layout::view($viewName, [
                    'setting' => $setting,
                    'valueEditField' => $this->buildSettingValueEditorField($setting),
                ]);
            }

            $tabLayouts[Str::headline($group)] = Layout::blank($settingEditors);
        }

        return Layout::tabs($tabLayouts);
    }

    private function buildSettingValueEditorField(Setting $setting): Field
    {
        $fieldType = FieldType::tryFrom($setting->getFieldType());

        $fieldOptionsFactory = $this->fieldFactoryRegistry->getFieldOptionsFactory($fieldType);
        $fieldOptions = $fieldOptionsFactory->fromArray($setting->getFieldOptions() ?: []);

        $fieldFactory = $this->fieldFactoryRegistry->getFieldFactory($fieldType);
        $field = $fieldFactory->buildField($fieldOptions);

        return $field
            ->set('name', "settings[{$setting->getKey()}]")
            ->set('value', $this->valueService->getValue($setting));
    }

    private function buildCreateSettingModal(): Modal
    {
        $modal = Layout::modal(self::MODAL_CREATE_SETTING, SettingEditLayout::class);

        return $modal->applyButton(__('Create'));
    }

    /**
     * @throws \JsonException
     */
    public function create(Request $request)
    {
        $settingData = $request->validate([
            'key' => [
                Rule::unique(Setting::class, 'key')
                    ->ignore($request->post('key')),
                'required',
                'string',
                'between:1,128',
                'regex:/[a-z0-9\-]+\.[a-z0-9_\-]+/',
            ],
            'description' => [
                'string',
            ],
            'fieldType' => [
                'required',
                'string',
                Rule::in($this->fieldFactoryRegistry->getFieldTypeValues()),
            ],
            'fieldOptions' => [
                'required',
                'string',
                'json',
            ],
            'value' => [
                'string',
                'nullable',
            ],
            'isEncrypted' => [
                'boolean',
            ],
        ]);

        $settingData['fieldOptions'] = json_decode($settingData['fieldOptions'], true, flags: JSON_THROW_ON_ERROR);

        $setting = new Setting();
        $setting = $this->settingFiller->fillFromArray($setting, $settingData);

        $this->valueService->setValue($setting, (string) $settingData['value']);
        $this->createService->create($setting);

        Toast::info(__('Setting :settingKey was created', ['settingKey' => $settingData['key']]));
    }

    public function save(Request $request): RedirectResponse
    {
        $settingsData = $request->get('settings');
        if (!is_array($settingsData)) {
            throw new BadRequestHttpException('Invalid arguments');
        }

        foreach ($settingsData as $settingKey => $value) {
            $setting = $this->readService->findByKey($settingKey);
            if (null === $setting) {
                throw new BadRequestHttpException(sprintf('Could not find setting "%s"', $settingKey));
            }

            $this->valueService->setValue($setting, $value);
            $this->updateService->update($setting);
        }

        Toast::info(__('Settings were successfully saved.'));

        return redirect()->route(self::getRoute());
    }

    public function remove(Request $request): void
    {
        $settingKey = $request->get('key');
        $setting = $this->readService->findByKey($settingKey);
        if (null === $setting) {
            throw new NotFoundHttpException(sprintf('Could not find setting "%s"', $settingKey));
        }

        $this->deleteService->delete($setting);

        Toast::info(__('Setting :settingKey was removed', ['settingKey' => $settingKey]));
    }
}
