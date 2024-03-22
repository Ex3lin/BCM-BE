<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTagRequest;
use App\Http\Requests\InvoiceCreateRequest;
use App\Http\Requests\InvoiceSumOfDatesRequest;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function createInvoice(InvoiceCreateRequest $request){
        if($request->has('task')){
            
        };

        if($request->has('repeat_count') && $request->has('repeat_interval')){
            $repeatCount = $request->input('repeat_count');
            $repeatInterval = $request->input('repeat_interval');
            $sinceDate = Carbon::now();

            for ($i = 0; $i < $repeatCount; $i++) {
                $invoice = new Invoice($request->all());
                $invoice['deadline'] = $sinceDate; 
                
                if ($repeatInterval == 'daily') {
                    $sinceDate->addDay();
                
                    $invoice->save();
                }
                if ($repeatInterval == 'weekly') {
                    $sinceDate->addWeek();
                
                    $invoice->save();
                }
                if ($repeatInterval == 'monthly') {
                    $sinceDate->addMonth();
                
                    $invoice->save();
                }
                
                $invoice->save();
            }
            

            return "Invoices created";
        }
        $data = $request->all();
        $invoice = new Invoice($data);
        $invoice->save(); 
        return "Invoice created";
    }
    
    public function getInvoices(Request $request){
        $invoiceQuery = Invoice::query();
        
        if ($request->has('status')) {
            $invoiceQuery->where('status','=',$request->input('status'));
        }
        if ($request->has('type')) {
            $invoiceQuery->where('type','=',$request->input('type'));
        }
        if ($request->has('orderBy') && $request->has('orderDir')){
            $orderBy = $request->input('orderBy');
            $orderDir = $request->input('orderDir','ASC');

            $invoiceQuery->orderBy($orderBy, $orderDir);
        }
        // $filter = $request->all();

        return $invoiceQuery->get();
        // return Invoice::with(['tags'])->get();
    }

    public function deleteInvoice($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return `Invoice $id has been deleted`;
    }

    public function updateInvoice(InvoiceCreateRequest $invoiceId, Invoice $invoice){

        $invoice->fill($invoiceId->all());
        $invoice->save();
        return $invoice;
    }

    public function getSumOfDates(InvoiceSumOfDatesRequest $request){
        $startOfDate = Carbon::createFromFormat('Y-m-d',$request->input('startDate'));
        $endDate = Carbon::createFromFormat('Y-m-d',$request->input('endDate'));

        $sumDatesByType = Invoice::query()
            ->select(
                DB::raw('SUM(cost) as cost,type'),
            )
            ->groupBy('type')
            ->where('deadline', '>', $startOfDate)
            ->where('deadline','<', $endDate)
            ->get();

        $sumIncome = $sumDatesByType->where('type','income')->max('cost');
        $sumExpense = $sumDatesByType->where('type','expense')->max('cost');

        $sumAll = $sumIncome-$sumExpense;


        return $sumAll;
    }

    public function syncTags(AttachTagRequest $request){
        $invoiceId = $request->input('invoice_id');
        $tagId = $request->input('tag_id');

        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->tags()->sync($tagId);
        return `Invoice $invoiceId connected with tag $tagId`;
    }

}
