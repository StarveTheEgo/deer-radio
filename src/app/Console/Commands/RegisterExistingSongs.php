<?php

namespace App\Console\Commands;

use App\Components\Storage\Enum\StorageName;
use App\Models\Song;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Orchid\Attachment\File;

class RegisterExistingSongs extends Command
{
    protected $signature = 'songs:migrate';

    protected $description = 'Command description';

    public function handle(): int
    {
        echo 'The script is disabled', PHP_EOL;
        exit;
        $input_fs = Storage::disk(StorageName::PUBLIC_STORAGE->value);
        $path = $input_fs->path('/tmp/');

        $wrong_counts = [];
        $songs = Song::all();
        /** @var Song $song */
        foreach ($songs as $song) {
            if (!empty($song->song_attachment_id)) {
                echo $song->title, ': skipping', PHP_EOL;
                continue;
            }
            $source_data = json_decode($song->source, flags: JSON_THROW_ON_ERROR);
            $source_count = count($source_data);
            $source = $source_data[array_key_last($source_data)];
            if (1 !== $source_count) {
                $wrong_counts[] = [
                    'title' => $song->title,
                    'source' => $source_data,
                ];
                if (0 === $source_count) {
                    continue;
                }
            }
            $input_path = $path.$source->download_link;
            if (!is_file($input_path)) {
                throw new LogicException('No file for '.$song->title);
            }

            $file = new UploadedFile($input_path, $source->original_name);
            $attachment = (new File($file))->path('/public/songs/'.date('Y-m-d', filemtime($input_path)).'/')->load();
            $song->song_attachment_id = $attachment->id;
            $song->save();
            echo $song->title, PHP_EOL;
        }
        echo '------', PHP_EOL;
        var_dump($wrong_counts);
        echo 'Done', PHP_EOL;

        return Command::SUCCESS;
    }
}
