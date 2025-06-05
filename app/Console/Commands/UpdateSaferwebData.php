<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mixins\Integrations\SaferwebAPI;
use App\Repositories\User\UserRepo;

class UpdateSaferwebData extends Command
{
    protected $signature = 'saferweb:update {--chunk=100} {--delay=2}';

    protected $description = 'Update users DOT info from SaferWeb API';

    protected SaferwebAPI $saferweb;
    protected UserRepo $userRepo;

    public function __construct(
        SaferwebAPI $saferweb
        )
    {
        parent::__construct();

        $this->saferweb = $saferweb;
        $this->userRepo = new UserRepo();
    }

    public function handle()
    {
        $chunkSize = (int)$this->option('chunk');
        $delay = (int)$this->option('delay');

        $users = $this->userRepo->getAll([], $paginate = 1000);

        foreach ($users['items'] as $user) {

            // Skip users without a company 
            if( $user['company'] == null ) { continue; }

            $dotNumber = $user['company']['dot_number'] ?? null;

            echo $dotNumber . "\n";


        }


        
        $this->info("SaferWeb user updates completed.");
    }
}
