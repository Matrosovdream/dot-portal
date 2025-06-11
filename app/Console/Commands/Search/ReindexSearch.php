<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;
use App\Models\Driver; 
use App\Models\File;

class ReindexSearch extends Command
{

    protected $signature = 'app:reindex-search';

    protected $description = 'Reindex the search index for all searchable models - Scout package';

    public function handle()
    {
        
        $this->info('Reindexing search data...');

        // Searchable models
        Driver::all()->searchable();
        File::all()->searchable();

        $this->info('Done!');

    }
}
