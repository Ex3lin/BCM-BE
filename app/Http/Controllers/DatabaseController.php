<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatabaseExchangeRequest;
use App\Models\Invoice;
use App\Models\InvoiceTags;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller

{
    /**
        *   @OA\Get(
        *       path="/api/DBexport",
        *       summary="Export database",
        *       tags={"Database"},
        *       description="Export all elements of database",
        *       @OA\Response(
        *           response=200, 
        *           description="Successful request"
        *       ),
        *   )
    */
    public function exportDatabase(){
        $imageName = "BCM-ImageDB_" . date('Ymd');
        $jsonData['BCM_ImageName'] = $imageName;

        $invoicesData = DB::table('invoices')->get()->toArray();
        $jsonData['invoices'] = $invoicesData;

        $tagsData = DB::table('tags')->get()->toArray();
        $jsonData['tags'] = $tagsData;

        $invoiceTagData = DB::table('invoice_tag')->get()->toArray();
        $jsonData['invoice_tag'] = $invoiceTagData;

        $jsonContent = json_encode($jsonData);

        $fileName = 'DB_image_' . date('Ymd') . '.json';
        Storage::disk('local')->put($fileName, $jsonContent);

        return Storage::download($fileName);
    }

    /**
        *   @OA\Post(
        *       path="api/DBimport",
        *       tags={"Database"},
        *       summary="Import database from .json file",
        *       @OA\RequestBody(
        *           @OA\MediaType(mediaType="application/json")
        *       ),
        *       @OA\Response(
        *           response=200,
        *           description="Database has been imported successfully",
        *       )
        *   )
    */
    public function importDatabase(DatabaseExchangeRequest $request){
        $jsonData = json_decode($request->getContent(), true);

        if($jsonData){
            DB::table('invoice_tag')->delete();
            DB::table('invoices')->delete();
            DB::table('tags')->delete();

            $this->importInvoices($jsonData['invoices']);
            $this->importTags($jsonData['tags']);
            $this->importInvoiceTags($jsonData['invoice_tag']);
            
            return 'Database has been imported successfully';
        }
        else{
            return 'Invalid JSON data';
        }
    }
    private function importInvoices($invoices){
        foreach($invoices as $invoice){
            Invoice::create($invoice);
        }
    }
    private function importTags($tags){
        foreach($tags as $tag){
            Tag::create($tag);
        }
    }
    private function importInvoiceTags($invoiceTags){
        foreach($invoiceTags as $invoiceTag){
            InvoiceTags::create($invoiceTag);
        }
    }
}