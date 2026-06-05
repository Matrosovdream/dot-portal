<?php

namespace App\Console\Commands;

use App\Services\DummyData\DummyConfig;
use App\Services\DummyData\DummyDataGeneratorService;
use Illuminate\Console\Command;
use Throwable;

class DummyDataGenerateCommand extends Command
{
    protected $signature = 'dummy:generate
        {--fresh : Wipe existing dummy data before generating}
        {--companies= : Number of dummy companies to generate}
        {--seed= : Faker seed for reproducible data}';

    protected $description = 'Generate realistic dummy business data (companies, drivers, vehicles, subscriptions, requests, orders, tasks).';

    public function handle(DummyDataGeneratorService $service): int
    {
        if ($this->option('fresh')) {
            $this->warn('Wiping existing dummy data...');
            $this->renderTable('Deleted', $service->wipe());
        }

        $opts = [];
        if ($this->option('companies') !== null) {
            $opts['companies'] = (int) $this->option('companies');
        }
        if ($this->option('seed') !== null) {
            $opts['seed'] = (int) $this->option('seed');
        }

        $this->info('Generating dummy data...');

        try {
            $summary = $service->run($opts);
        } catch (Throwable $e) {
            $this->error('Dummy data generation failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->renderTable('Generated', $summary);

        $this->info(sprintf(
            'Done. Sign in with a dummy company (e.g. %s) using password "%s".',
            DummyConfig::companyEmail(1),
            DummyConfig::PASSWORD,
        ));

        return self::SUCCESS;
    }

    private function renderTable(string $header, array $summary): void
    {
        $rows = [];
        foreach ($summary as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subVal) {
                    $rows[] = ["{$key}.{$subKey}", $subVal];
                }
            } else {
                $rows[] = [$key, $value];
            }
        }

        $this->table(['Entity', $header], $rows);
    }
}
