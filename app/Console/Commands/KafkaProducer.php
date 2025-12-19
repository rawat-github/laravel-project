<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KafkaProducer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kafka-producer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('KafkaProducer command executed!');
    }
}
