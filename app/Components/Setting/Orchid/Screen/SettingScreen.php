<?php

declare(strict_types=1);

namespace App\Components\Setting\Orchid\Screen;

use App\Components\OrchidIntergration\Field\FieldFactoryRegistry;
use App\Components\OrchidIntergration\Field\FieldOptions;
use App\Components\OrchidIntergration\Field\FieldType;
use App\Components\Setting\Entity\Setting;
use App\Components\Setting\Service\SettingServiceRegistry;
use App\Components\Setting\SettingNameInfo;
use App\Components\Setting\SettingServiceProvider;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Tabs;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingScreen extends AbstractScreen
{
    private SettingServiceRegistry $settingServiceRegistry;

    private FieldFactoryRegistry $fieldFactoryRegistry;

    private const MODAL_CREATE_SETTING = 'createSettingModal';

    private ?array $cachedQuery = null;

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
        SettingServiceRegistry $settingServiceRegistry,
        FieldFactoryRegistry $fieldFactoryRegistry
    ) {
        $this->settingServiceRegistry = $settingServiceRegistry;
        $this->fieldFactoryRegistry = $fieldFactoryRegistry;
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
            $settings = $this->settingServiceRegistry->getReadService()->filteredFindAll($this->filters());
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
        $fieldFactory = $this->fieldFactoryRegistry->getFactory($fieldType);

        $fieldOptions = FieldOptions::fromArray($setting->getFieldOptions() ?: []);
        $field = $fieldFactory->buildField($fieldOptions);

        return $field
            ->set('name', "settings[{$setting->getKey()}]")
            ->set('value', $this->settingServiceRegistry->getValueService()->getValue($setting));
    }

    private function buildCreateSettingModal(): Modal
    {
        $layout = Layout::rows([
            Input::make('key')
                ->title('Setting key')
                ->placeholder('group-name.setting_name')
                ->help('Unique key of the setting. Must have format <code>group-name.setting_name</code>')
                ->required(),

            Input::make('description')
                ->title('Description')
                ->placeholder('Some incredible description'),

            Select::make('field_type')
                ->title('Field type')
                ->options($this->fieldFactoryRegistry->getTypeTitles())
                ->required(),

            Code::make('field_options')
                ->title('Field options')
                ->language(Code::JS)
                ->value("{\n    \n}")
                ->required(),

            Input::make('value')
                ->title('Initial value'),

            CheckBox::make('is_encrypted')
                ->title('Keep the value encrypted')
                ->sendTrueOrFalse(),
        ]);

        $modal = Layout::modal(self::MODAL_CREATE_SETTING, $layout);

        return $modal->applyButton(__('Create'));
    }

    public function create(Request $request)
    {
        $settingData = $request->validate([
            'key' => [
                'required',
                'string',
                'between:1,128',
                'regex:/[a-z0-9\-]+\.[a-z0-9_\-]+/',
            ],
            'description' => [
                'string',
            ],
            'field_type' => [
                'required',
                'string',
                Rule::in($this->fieldFactoryRegistry->getTypeValues()),
            ],
            'field_options' => [
                'required',
                'string',
                'json',
            ],
            'value' => [
                'string',
                'nullable',
            ],
            'is_encrypted' => [
                'boolean',
            ],
        ]);

        $setting = new Setting();
        $setting->setKey($settingData['key']);
        $setting->setDescription($settingData['description']);
        $setting->setFieldType($settingData['field_type']);
        $setting->setFieldOptions(json_decode($settingData['field_options'], true, flags: JSON_THROW_ON_ERROR));
        $setting->setIsEncrypted((bool) $settingData['is_encrypted']);
        $setting->setOrd(0); // @todo sorting

        $this->settingServiceRegistry->getValueService()->setValue($setting, (string) $settingData['value']);
        $this->settingServiceRegistry->getCreateService()->create($setting);

        Toast::info(__('Setting :settingKey was created', ['settingKey' => $settingData['key']]));
    }

    public function save(Request $request): RedirectResponse
    {
        $settingsData = $request->get('settings');
        if (!is_array($settingsData)) {
            throw new BadRequestHttpException('Invalid arguments');
        }

        foreach ($settingsData as $settingKey => $value) {
            $setting = $this->settingServiceRegistry->getReadService()->findByKey($settingKey);
            if (null === $setting) {
                throw new BadRequestHttpException(sprintf('Could not find setting "%s"', $settingKey));
            }

            $this->settingServiceRegistry->getValueService()->setValue($setting, $value);
            $this->settingServiceRegistry->getUpdateService()->update($setting);
        }

        Toast::info(__('Settings were successfully saved.'));

        return redirect()->route(self::getRoute());
    }

    public function remove(Request $request): void
    {
        $settingKey = $request->get('key');
        $setting = $this->settingServiceRegistry->getReadService()->findByKey($settingKey);
        if (null === $setting) {
            throw new NotFoundHttpException(sprintf('Could not find setting "%s"', $settingKey));
        }

        $this->settingServiceRegistry->getDeleteService()->delete($setting);

        Toast::info(__('Setting :settingKey was removed', ['settingKey' => $settingKey]));
    }
}
