<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MeiliSearch\Client;

class MeilisearchFilterableAttributesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:filterables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Meilisearch Engine filterable attributes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Client $client)
    {
        $artisanAttributes = [
            'id',
            'business_name',
            'email',
            'phone',
            'address',
            'profile',
            'city_name',
            'state_name',
            'category'
        ];

        $client->index('artisans')->updateFilterableAttributes(filterableAttributes: $artisanAttributes);
        $this->info('Meilisearch filterable attributes updated for artisans index');

        return 0;
    }
}
