<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Models\Tag;
use Illuminate\Routing\Controller;

class TagController extends Controller
{
    
    public function create(TagCreateRequest $request){
        $data = $request->all();
        $tag = new Tag($data);
        $tag->save(); 
        return $tag;
    }
    
    public function get(){
        return Tag::all();
    }

    public function delete($id){
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return `тег $id удален`;
    }
}