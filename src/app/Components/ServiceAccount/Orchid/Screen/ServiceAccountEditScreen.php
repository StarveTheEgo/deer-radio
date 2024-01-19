<?php

declare(strict_types=1);

namespace App\Components\ServiceAccount\Orchid\Screen;

use App\Components\OrchidIntergration\Helper\PrefixHelper;
use App\Components\ServiceAccount\Entity\ServiceAccount;
use App\Components\ServiceAccount\Enum\ServiceAccountRoute;
use App\Components\ServiceAccount\Enum\ServiceName;
use App\Components\ServiceAccount\Filler\ServiceAccountFiller;
use App\Components\ServiceAccount\Orchid\Enum\ServiceAccountPermission;
use App\Components\ServiceAccount\Orchid\Enum\ServiceAccountScreenTarget;
use App\Components\ServiceAccount\Orchid\Layout\ServiceAccountEditLayout;
use App\Components\ServiceAccount\Service\ServiceAccountCreateService;
use App\Components\ServiceAccount\Service\ServiceAccountReadService;
use App\Components\ServiceAccount\Service\ServiceAccountUpdateService;
use App\Components\User\Service\UserReadService;
use App\Models\User as LaravelUser;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JsonException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layout;
use Orchid\Support\Facades\Toast;
use Webmozart\Assert\Assert;

class ServiceAccountEditScreen extends AbstractScreen
{
    private ServiceAccountFiller $serviceAccountFiller;

    private ServiceAccountCreateService $createService;

    private ServiceAccountReadService $readService;

    private ServiceAccountUpdateService $updateService;
    private UserReadService $userReadService;

    /**
     * @return string|null
     */
    public static function getName(): ?string
    {
        return __('Manage service accounts');
    }

    /**
     * @return string
     */
    public static function getRoute(): string
    {
        return ServiceAccountRoute::EDIT->value;
    }

    /**
     * @return array<string>|null
     */
    public static function getPermissions(): ?array
    {
        return [
            ServiceAccountPermission::CAN_EDIT->value,
        ];
    }

    /**
     * @param ServiceAccountFiller $serviceAccountFiller
     * @param ServiceAccountCreateService $createService
     * @param ServiceAccountReadService $readService
     * @param ServiceAccountUpdateService $updateService
     * @param UserReadService $userReadService
     */
    public function __construct(
        ServiceAccountFiller $serviceAccountFiller,
        ServiceAccountCreateService $createService,
        ServiceAccountReadService $readService,
        ServiceAccountUpdateService $updateService,
        UserReadService $userReadService
    ) {
        $this->serviceAccountFiller = $serviceAccountFiller;
        $this->createService = $createService;
        $this->readService = $readService;
        $this->updateService = $updateService;
        $this->userReadService = $userReadService;
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return __('Service account management');
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
     * @param ServiceAccount|null $serviceAccount
     * @return iterable
     */
    public function query(?ServiceAccount $serviceAccount): iterable
    {
        return [
            ServiceAccountScreenTarget::CURRENT_ACCOUNT->value => $serviceAccount,
        ];
    }

    /**
     * @return string[]|Layout[]
     */
    public function layout(): iterable
    {
        return [
            ServiceAccountEditLayout::class,
        ];
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    private function rules(Request $request): array
    {
        $prefix = sprintf(
            '%s.',
            ServiceAccountScreenTarget::CURRENT_ACCOUNT->value
        );

        /** @var LaravelUser $currentUser */
        $currentUser = $request->user();
        Assert::notNull($currentUser);

        $serviceAccountInput = $request->get(ServiceAccountScreenTarget::CURRENT_ACCOUNT->value) ?? [];

        return PrefixHelper::addPrefixToArrayKeys($prefix,[
            'id' => [
                'string',
                'nullable',
            ],
            'accountName' => [
                'required',
                Rule::unique(ServiceAccount::class, 'accountName')
                    ->where('user', $currentUser->id)
                    ->where('accountName', $serviceAccountInput['accountName'])
                    ->ignore($serviceAccountInput['id'] ?? null),
            ],
            'serviceName' => [
                'required',
                Rule::enum(ServiceName::class),
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
        /** @var LaravelUser $currentLaravelUser */
        $currentLaravelUser = $request->user();
        Assert::notNull($currentLaravelUser);

        $currentUser = $this->userReadService->getById($currentLaravelUser->id);

        $validatedData = $request->validate($this->rules($request));

        $dataTargetKey = ServiceAccountScreenTarget::CURRENT_ACCOUNT->value;
        $serviceAccountData = $validatedData[$dataTargetKey];
        $isNew = empty($serviceAccountData['id']);

        if ($isNew) {
            // create new account
            $serviceAccount = new ServiceAccount();
            $serviceAccount->setUser($currentUser);
        } else {
            // load existing account
            $serviceAccount = $this->readService->getById((int) $serviceAccountData['id']);

            // account id should not be changed in this controller
            Assert::notEq($serviceAccount->getUser()->getId(), $currentUser->getId());

            // let's additionally check that driver is not changed
            if ($serviceAccount->getServiceName() !== $serviceAccountData['serviceName']) {
                $prefix = sprintf(
                    '%s.',
                    $dataTargetKey
                );
                throw ValidationException::withMessages(PrefixHelper::addPrefixToArrayKeys($prefix, [
                   'serviceName' => 'Service name cannot be changed. Please, create a new service account',
                ]));
            }
        }

        $this->serviceAccountFiller->fillFromArray($serviceAccount, $serviceAccountData);

        // creating or updating object in the storage via service
        if ($isNew) {
            $this->createService->create($serviceAccount);
        } else {
            $this->updateService->update($serviceAccount);
        }

        Toast::info(__('Account was saved.'));

        return redirect()->route(ServiceAccountRoute::INDEX->value);
    }
}
