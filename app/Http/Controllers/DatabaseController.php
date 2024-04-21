<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller
{
    public function exportDatabase(){
        $invoicesData = DB::table('invoices')->get()->toArray();
        $jsonData['invoices'] = $invoicesData;

        $tagsData = DB::table('tags')->get()->toArray();
        $jsonData['tags'] = $tagsData;

        $invoiceTagData = DB::table('invoice_tag')->get()->toArray();
        $jsonData['invoice_tag'] = $invoiceTagData;

        $jsonContent = json_encode($jsonData);

        $fileName = 'DB_image_' . date('YYYYmmdd') . '.json';
        Storage::disk('local')->put($fileName, $jsonContent);

        return Storage::download($fileName);
    }

}