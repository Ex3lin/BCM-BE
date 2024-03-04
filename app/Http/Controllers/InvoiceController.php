<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTagRequest;
use App\Http\Requests\InvoiceCreateRequest;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function createInvoice(InvoiceCreateRequest $request){
        $data = $request->all();
        $invoice = new Invoice($data);
        $invoice->save(); 
        return "Запись создана успешно";
    }
    
    public function getInvoices(){
        return Invoice::with(['tags'])->get();
    }

    public function deleteInvoice($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return `Инвойс $id удален`;
    }

    public function updateInvoice(InvoiceCreateRequest $request, Invoice $invoice){
        $invoice->fill($request->all());
        $invoice->save();
        return $invoice;
    }

    public function syncTags(AttachTagRequest $request){
        $invoice = Invoice::findOrFail($request->input('invoice_id'));
        $invoice->tags()->sync($request->input('tag_id'));
    }

}
