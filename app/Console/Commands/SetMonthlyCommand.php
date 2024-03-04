<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\ScheduleInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetMonthlyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'schedule:monthly';
    /**
     * The console command description.
     * @var string
     */

    protected $description = 'Set monthly schedule';

    public function handle()
    {
        $allDailies = ScheduleInvoice::where('repetition','monthly')
            ->where('start_date','<=',Carbon::now())
            ->get();

        foreach ($allDailies as $monthlyInvoice) {
            $newInvoice = new Invoice([
                'timestamp' => $monthlyInvoice['start_date'],
                'type' => $monthlyInvoice['type'],
                'name' => 'monthlyEvent',
                'cost' => $monthlyInvoice['quantity']
            ]);
            $monthlyInvoice['quantity']--;
            $monthlyInvoice->save();

            $newInvoice->save();
        }
    }
}
