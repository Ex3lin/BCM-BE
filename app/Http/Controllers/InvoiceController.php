<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTagRequest;
use App\Http\Requests\InvoiceCreateRequest;
use App\Models\Invoice;
use App\Models\InvoiceTags;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create(InvoiceCreateRequest $request){
        $data = $request->all();
        $invoice = new Invoice($data);
        $invoice->save(); 
        return "Запись создана успешно";
    }
    
    public function get(){
        return Invoice::with(['tags'])->get();
    }

    public function delete($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return `Инвойс $id удален`;
    }

    public function syncTags(AttachTagRequest $request){
        $invoice = Invoice::findOrFail($request->input('invoice_id'));
        $invoice->tags()->sync($request->input('tag_id'));
    }

}
