<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTagRequest;
use App\Http\Requests\ScheduleInvoiceCreateRequest;
use App\Models\ScheduleInvoice;

class ScheduleInvoiceController extends Controller
{
    public function create(ScheduleInvoiceCreateRequest $request){
        $data = $request->all();
        $invoice = new ScheduleInvoice($data);
        $invoice->save();
        return "Расписание задано";
    }

    public function get(){
        return ScheduleInvoice::all();
    }

    public function delete($id){
        $invoice = ScheduleInvoice::findOrFail($id);
        $invoice->delete();
        return `Расписание с $id удален`;
    }

    // public function sync(AttachRequest $request){
    //     $invoice = ScheduleInvoice::findOrFail($request->input('invoice_id'));
    //     $invoice->schedule()->sync($request->input('schedule_id'));
    // }

}
