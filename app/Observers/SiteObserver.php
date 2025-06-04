<?php

namespace App\Observers;

use App\Models\Site;
use App\Services\GoogleSheetsService;

class SiteObserver
{
    /**
     * Handle the Site "created" event.
     */
    public function created(Site $site): void
    {
        //
    }

    /**
     * Handle the Site "updated" event.
     */
    public function updated(Site $site): void
    {
        //
    }

    /**
     * Handle the Site "deleted" event.
     */
    public function deleted(Site $site): void
    {
        app(GoogleSheetsService::class)->deleteRowsByDomain($site->domain);
    }

    /**
     * Handle the Site "restored" event.
     */
    public function restored(Site $site): void
    {
        //
    }

    /**
     * Handle the Site "force deleted" event.
     */
    public function forceDeleted(Site $site): void
    {
        //
    }
}
