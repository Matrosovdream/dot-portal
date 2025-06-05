<?php

namespace App\Jobs;

use App\Models\User;
use App\Mixins\Integrations\SaferwebAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use App\Repositories\User\UserRepo;

class UpdateUserFromSaferweb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    protected int $userId;
    protected $userRepo;
    protected $saferweb;

    public function __construct(int $userId)
    {
        $this->userId = $userId;

        $this->userRepo = new UserRepo();
        $this->saferweb = new SaferwebAPI();
    }

    public function handle(SaferwebAPI $saferweb): void
    {
     
        $user = $this->userRepo->getByID($this->userId);

        $dotNumber = $user['company']['dot_number'] ?? null;

            $apiData = $this->saferweb->getCompanySnapshot($dotNumber);

            if( $apiData != null ) { 
                $user['Model']->company->update([
                    'mc_number' => $apiData['phone'],
                ]);
            }

    }

}
