<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class SetFileCache extends Command
{
    protected $signature = 'cache:set-file';
    protected $description = 'Set cache driver to file';

    public function handle()
    {
        Config::set('cache.default', 'file');
        $this->info('Cache driver set to file');
        return 0;
    }
}
