<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Commands;

use App\Components\DeerRadio\Service\DeerImageDeleteService;
use App\Components\DeerRadio\Service\DeerImageUpdateService;
use Exception;
use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;

/**
 * Command to update current deer image to display on the Deer Radio
 */
class DeerImageUpdate extends Command
{
    use WithoutOverlapping;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deer-image:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates deer image using Unsplash API (or another service!)';

    private DeerImageUpdateService $deerImageUpdateService;
    private DeerImageDeleteService $deerImageDeleteService;

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function __construct(
        DeerImageUpdateService $deerImageUpdateService,
        DeerImageDeleteService $deerImageDeleteService
    )
    {
        parent::__construct();

        $this->deerImageUpdateService = $deerImageUpdateService;
        $this->deerImageDeleteService = $deerImageDeleteService;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function handle(): bool
    {
        $this->deerImageDeleteService->removeOldImages();
        $this->deerImageUpdateService->update();

        return true;
    }
}
