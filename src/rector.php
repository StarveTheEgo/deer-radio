<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84,
    ]);

    $rectorConfig->parallel(240, 2);

    $rectorConfig->skip([
        __DIR__ . '/vendor/*'
    ]);
};
