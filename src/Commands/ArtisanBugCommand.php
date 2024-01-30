<?php

namespace Grafst\ArtisanBug\Commands;

use Illuminate\Console\Command;

class ArtisanBugCommand extends Command
{
    public $signature = 'artisan-bug';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
