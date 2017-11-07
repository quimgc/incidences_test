<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Quimgc\Incidences\src\Models\Incidence;

class CreateIncidenceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incidence:create {name?  : The Incidence name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{

            Incidence::create([
                'name'=>$this->argument('name') ? $this->argument('name') : $this->ask('Incidence name?')
            ]);
        } catch ( Exception $e) {
            $this->error('error' . $e);
        }

        $this->info('Task has been added to database succesfully');

    }
}
