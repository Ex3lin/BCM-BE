<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Models\Tag;
use Illuminate\Routing\Controller;

class TagController extends Controller
{
    public function create(TagCreateRequest $request){
        $data = $request->all();
        $invoice = new Tag($data);
        $invoice->save(); 
        return "Тег добавлен";
    }
    
    public function get(){
        return Tag::all();
    }

    public function delete($id){
        $invoice = Tag::findOrFail($id);
        $invoice->delete();
        return `тег $id удален`;
    }
}