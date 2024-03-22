<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Invoice::factory(10)->create();
    }
}
