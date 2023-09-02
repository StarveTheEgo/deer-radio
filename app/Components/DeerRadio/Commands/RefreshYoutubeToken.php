<?php

namespace App\Console\Commands;

use App\DeerRadio\YoutubeApi;
use Illuminate\Console\Command;

class RefreshYoutubeToken extends Command {

    protected $signature = 'youtube-token:refresh';
    protected $description = 'Refreshes YouTube OAuth token';
    /** @var YoutubeApi */
    private $youtubeApi;

    public function __construct() {
        parent::__construct();
        $this->youtubeApi = new YoutubeApi();
    }

    public function handle() {
        $this->youtubeApi->refreshToken();
    }
}
