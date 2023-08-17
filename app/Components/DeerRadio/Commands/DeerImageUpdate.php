<?php

declare(strict_types=1);

namespace App\Components\DeerRadio\Commands;

use App\Components\DeerRadio\DeerImageManager;
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

    private DeerImageManager $deerImageManager;

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function __construct(DeerImageManager $deerImageManager)
    {
        $this->deerImageManager = $deerImageManager;
        parent::__construct();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function handle(): bool
    {
        $this->deerImageManager->removeOldImages();
        $this->deerImageManager->update();

        return true;
    }
}
