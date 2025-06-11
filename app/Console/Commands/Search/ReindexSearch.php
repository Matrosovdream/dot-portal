<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;
use App\Models\Driver; 

class ReindexSearch extends Command
{

    protected $signature = 'app:reindex-search';

    protected $description = 'Reindex the search index for all searchable models - Scout package';

    public function handle()
    {
        
        $this->info('Reindexing search data...');

        // Driver model
        Driver::all()->searchable();

        $this->info('Done!');

    }
}
