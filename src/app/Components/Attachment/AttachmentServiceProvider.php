<?php

declare(strict_types=1);

namespace App\Components\Attachment;

use App\Components\Attachment\Entity\Attachment;
use App\Components\Attachment\Repository\AttachmentRepository;
use App\Components\Attachment\Repository\AttachmentRepositoryInterface;
use App\Components\Attachment\Service\AttachmentCreateService;
use App\Components\Attachment\Service\AttachmentDeleteService;
use App\Components\Attachment\Service\AttachmentReadService;
use App\Components\Attachment\Service\AttachmentUpdateService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AttachmentServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons = [
        AttachmentCreateService::class => AttachmentCreateService::class,
        AttachmentReadService::class => AttachmentReadService::class,
        AttachmentUpdateService::class => AttachmentUpdateService::class,
        AttachmentDeleteService::class => AttachmentDeleteService::class,
    ];

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AttachmentRepositoryInterface::class, function (Application $app) {
            $em = $app->get(EntityManager::class);
            $entityRepository = new EntityRepository($em, $em->getClassMetaData(Attachment::class));

            return new AttachmentRepository(
                $em,
                $entityRepository
            );
        });
    }

    /**
     * @inheritDoc
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            AttachmentCreateService::class,
            AttachmentReadService::class,
            AttachmentUpdateService::class,
            AttachmentDeleteService::class,
            AttachmentRepositoryInterface::class,
        ];
    }
}
