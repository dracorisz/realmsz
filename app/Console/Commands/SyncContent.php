<?php

namespace App\Console\Commands;

use App\Services\ContentSyncService;
use Illuminate\Console\Command;

class SyncContent extends Command
{
    protected $signature = 'content:sync';
    protected $description = 'Sync content with dracoscopia.com';

    protected $contentSyncService;

    public function __construct(ContentSyncService $contentSyncService)
    {
        parent::__construct();
        $this->contentSyncService = $contentSyncService;
    }

    public function handle()
    {
        $this->info('Starting content sync...');

        if ($this->contentSyncService->syncContent()) {
            $this->info('Content sync completed successfully.');
        } else {
            $this->error('Content sync failed. Check the logs for details.');
        }
    }
} 