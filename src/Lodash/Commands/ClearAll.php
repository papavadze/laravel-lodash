<?php

declare(strict_types=1);

namespace Longman\LaravelLodash\Commands;

use Illuminate\Console\Command;

class ClearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets all of compiled class files, cached views, cached configs etc.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $this->call('clear-compiled');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
    }
}
