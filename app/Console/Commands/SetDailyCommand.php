<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\ScheduleInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'schedule:daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allDailies = ScheduleInvoice::where('repetition','daily')
            ->where('deadline','<=',Carbon::now())
            ->get();

        foreach ($allDailies as $dailyInvoice) {
            $newInvoice = new Invoice([
                'timestamp' => $dailyInvoice['deadline'],
                'type' => $dailyInvoice['type'],
                'duration' => $dailyInvoice['duration'],
                'name' => 'dailyEvent',
                'cost' => 123
            ]);
            $dailyInvoice['duration']--;
            $dailyInvoice->save();

            $newInvoice->save();
        }
    }
}
