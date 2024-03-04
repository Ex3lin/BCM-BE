<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceTags;

class InvoiceTagController extends Controller
{
    public function get() {
        $invioces = InvoiceTags::all();
        return "Экспортировано";
    }

}
