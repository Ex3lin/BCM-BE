<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Models\Tag;
use Illuminate\Routing\Controller;

class TagController extends Controller
{

    /**
        *   @OA\Post(
        *       path="api/tag",
        *       tags={"Tag"},
        *       summary="Create new invoice",
        *       @OA\RequestBody(
        *           @OA\MediaType(
        *               mediaType="application/json",
        *               @OA\Schema(
        *                   required={
        *                       "name",
        *                       "type",
        *                       "cost"
        *                   },
        *                   @OA\Property(
        *                       property="name",
        *                       type="string",
        *                       description=""
        *                   ),
        *                   @OA\Property(
        *                       property="description",
        *                       type="string",
        *                       description=""
        *                   ),
        *                   @OA\Property(
        *                       property="cost",
        *                       type="integer",
        *                       description="only positive number"
        *                   ),
        *                   @OA\Property(
        *                       property="type",
        *                       type="string",
        *                       description="enum: task, income, expense"
        *                   ),
        *                   @OA\Property(
        *                       property="deadline",
        *                       type="date",
        *                       description="assigned link date for invoice"
        *                   )
        *               )
        *           )
        *       ),
        *       @OA\Response(
        *           @OA\MediaType(
        *               mediaType="application/json",
        *               @OA\Schema(
        *                   @OA\Property(
        *                       property="id",
        *                       type="integer"
        *                   ),
        *                   @OA\Property(
        *                       property="name",
        *                       type="string"
        *                   ),
        *                   @OA\Property(
        *                       property="updated_at",
        *                       type="date",
        *                       description=""
        *                   ),
        *                   @OA\Property(
        *                       property="created_at",
        *                       type="date",
        *                       description=""
        *                   )
        *               )
        *           ),
        *           response=200,
        *           description="Invoice",
        *       )
        *   )
    */
    public function create(TagCreateRequest $request){
        $data = $request->all();
        $tag = new Tag($data);
        $tag->save(); 
        return $tag;
    }

    /**
        *   @OA\Get(
        *       path="/api/tags",
        *       summary="Get tags",
        *       tags={"Tag"},
        *       description="Get all tags",
        *       @OA\Response(
        *           @OA\MediaType(
        *               mediaType="application/json",
        *               @OA\Schema(
        *                   @OA\Property(
        *                       property="id",
        *                       type="integer"
        *                   ),
        *                   @OA\Property(
        *                       property="name",
        *                       type="string"
        *                   ),
        *                   @OA\Property(
        *                       property="updated_at",
        *                       type="date",
        *                       description=""
        *                   ),
        *                   @OA\Property(
        *                       property="created_at",
        *                       type="date",
        *                       description=""
        *                   )
        *               )
        *           ),
        *           response=200,
        *           description="Invoice",
        *       ),
        *   )
    */
    public function get(){
        return Tag::all();
    }

    /**
        *   @OA\Delete(
        *       path="/api/tag/{tagId}",
        *       tags={"Tag"},
        *       summary="Delete tag by id",
        *       operationId="deleteTag",
        *       @OA\Parameter(
        *           name="tagId",
        *           in="path",
        *           description="Tag id to delete",
        *           required=true,
        *           @OA\Schema(
        *               type="integer",
        *               format="integer"
        *           ),
        *       ),
        *       @OA\Response(
        *           response=400,
        *           description="Invalid ID supplied",
        *       ),
        *       @OA\Response(
        *           response=404,
        *           description="Invoice not found",
        *       ),
        *   )
    */
    public function delete($id){
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return `тег $id удален`;
    }
}