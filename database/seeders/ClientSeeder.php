<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Card;

class ClientSeeder extends Seeder
{
    private $clients_amount = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::factory()
            ->times($this->clients_amount)
            ->has(Card::factory()->count(1)->state(function (array $attributes, Client $client) {
                return [
                    'default' => 1,
                    'client_id' => $client->id,
                    'name' => $client->name
                ];
            }))
            ->has(Card::factory()->count(2)->state(function (array $attributes, Client $client) {
                return [
                    'default' => 0,
                    'client_id' => $client->id,
                    'name' => $client->name
                ];
            }))
            ->create();
    }
}
