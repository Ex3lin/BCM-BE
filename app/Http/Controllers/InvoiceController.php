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
/**
 * @OA\Post(
 *     path="api/invoice",
 *     tags={"Invoice"},
 *     summary="Create new invoice",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={
 *                      "name",
 *                      "type",
 *                      "cost"
 *                 },
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="cost",
 *                     type="integer",
 *                     description="only positive number"
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="enum: task, income, expense"
 *                 ),
 *                 @OA\Property(
 *                     property="deadline",
 *                     type="date",
 *                     description="assigned link date for invoice"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string"
 *                 ),
 *                    @OA\Property(
 *                     property="cost",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="enum: task, income, expense",
 *                 ),
 *                 @OA\Property(
 *                     property="deadline",
 *                     type="date",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     description="enum: active, completed, aborted"
 *                 ),
 *                 @OA\Property(
 *                     property="updated_at",
 *                     type="date",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="created_at",
 *                     type="date",
 *                     description=""
 *                 )
 *             )
 *         ),
 *         response=200,
 *         description="Invoice",
 *         )
 *     )
 * )
 */
    public function createInvoice(InvoiceCreateRequest $request){
        $request['status'] = 'active';
        if($request->type === 'task'){
            $request->merge(['cost' => '0']);
        };
        if($request->type === 'expense'){
            $negative = $request->cost * -1;
            $request->merge(['cost' => $negative]);
        }

        if($request->has('repeat_count') && $request->has('repeat_interval')){
            $repeatCount = $request->input('repeat_count');
            $repeatInterval = $request->input('repeat_interval');
            $sinceDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->deadline);

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
                if ($repeatInterval == 'yearly') {
                    $sinceDate->addYear();
                    $invoice->save();
                }
                $invoice->save();
            }
            
            return $invoice;
        }
        $data = $request->all();
        $invoice = new Invoice($data);
        $invoice->save(); 

        return "Invoice created";
    }
    
/**
    * @OA\Get(
    *      path="/api/invoices",
    *      summary="Get invoices",
    *      tags={"Invoice"},
    *      description="Get invoices by query",
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="page", 
    *            description="",
    *            @OA\Schema(
    *                type="integer"
    *            )
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="minCost", 
    *            description="",
    *            @OA\Schema(
    *                type="integer"
    *            )
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="maxCost", 
    *            description="",
    *            @OA\Schema(
    *                type="integer"
    *            )
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="type", 
    *            description="Status values that need to be considered for filter",
    *            @OA\Schema(
    *                type="string",
    *                default="task",
    *                enum={"task", "income", "expense"}
    *            ),
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="status", 
    *            description="Status values that need to be considered for filter",
    *            @OA\Schema(
    *                type="string",
    *                default="active",
    *                enum={"active", "completed", "aborted"}
    *            ),
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="search", 
    *            description="Search by name, description, cost or tags",
    *            @OA\Schema(
    *                type="string"
    *            )
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="orderBy", 
    *            @OA\Schema(
    *                type="string",
    *                default="status",
    *                enum={"status", "deadline", "type", "cost"}
    *            ),
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=false, 
    *            name="orderDir", 
    *            @OA\Schema(
    *                type="string",
    *                default="ASC",
    *                enum={"ASC", "DESC"}
    *            ),
    *        ),
    *      @OA\Parameter(in="query", required=false, name="search", @OA\Schema(type="string")),
    *      @OA\Response(
    *            response=200, 
    *            description="Successful request"
    *        ),
    * )
 */
    public function getInvoices(Request $request){
        $invoiceQuery = Invoice::query();
        
        if ($request->has('type')) {
            $invoiceQuery->where('type','=',$request->input('type'));
        }
        if ($request->has('status')) {
            $invoiceQuery->where('status','=',$request->input('status'));
        }
        if ($request->has('minCost')) {
            $invoiceQuery->where('cost','>=',$request->input('minCost'));
        }
        if ($request->has('maxCost')) {
            $invoiceQuery->where('cost','<=',$request->input('maxCost'));
        }

        if ($request->has('orderBy') && $request->has('orderDir')){
            $orderBy = $request->input('orderBy');
            $orderDir = $request->input('orderDir','ASC');

            $invoiceQuery->orderBy($orderBy, $orderDir);
        }

        if ($request->has('search')) {
            $searchQuery = "%".$request->input('search')."%";
            $invoiceQuery->where('name','like',$searchQuery)
                ->orWhere('description','like',$searchQuery)
                ->orWhere('cost','like',$searchQuery)
                ->orWhereHas('tags', function ($query) use ($searchQuery) {
                    $query->where('name','like',$searchQuery);
                });
        }
        return $invoiceQuery->with(['tags'])->paginate(25);
    }
    /**
     * @OA\Delete(
     *     path="/api/invoice/{invoiceId}",
     *     tags={"Invoice"},
     *     summary="Delete invoice by id",
     *     operationId="deleteInvoice",
     *     @OA\Parameter(
     *         name="invoiceId",
     *         in="path",
     *         description="Invoice id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *     ),
     * )
     */
    public function deleteInvoice($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return "anal content";
    }

 /**
 * @OA\Patch(
 *     path="api/invoice/{InvoiceId}",
 *     tags={"Invoice"},
 *     summary="Update data for invoice by id",
 *     @OA\Parameter(
 *            in="path", 
 *            required=true, 
 *            name="InvoiceId", 
 *            description="",
 *            @OA\Schema(
 *                type="integer"
 *            )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={},
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="cost",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="enum: task, income, expense",
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     description="enum: active, completed, aborted",
 *                 ),
 *                 @OA\Property(
 *                     property="deadline",
 *                     type="date"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string"
 *                 ),
 *                    @OA\Property(
 *                     property="cost",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="enum: task, income, expense",
 *                 ),
 *                 @OA\Property(
 *                     property="deadline",
 *                     type="date",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     description="enum: active, completed, aborted"
 *                 ),
 *                 @OA\Property(
 *                     property="updated_at",
 *                     type="date",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="created_at",
 *                     type="date",
 *                     description=""
 *                 )
 *             )
 *         ),
 *         response=200,
 *         description="Invoice",
 *         )
 *     )
 * )
 */
    public function updateInvoice(InvoiceCreateRequest $request, Invoice $invoice){
        if($request->type === 'task'){
            $request->merge(['cost' => '0']);
        };
        if($request->type === 'expense'){
            $decr = $request->cost * -1;
            $request->merge(['cost' => $decr]);
        }


        if($request->status === 'completed' || $request->status === 'aborted'){
            $request->merge(['submitted' => Carbon::now()]);   
        }
        $invoice->fill($request->all());
        $invoice->save();

        return $invoice;
    }

/**
    * @OA\Get(
    *      path="/api/summary",
    *      summary="Get summary invoices cost count",
    *      tags={"Invoice"},
    *      description="Get summary by dates",
    *      @OA\Parameter(
    *            in="path", 
    *            required=true, 
    *            name="startDate", 
    *            description="format: HHHH-mm-dd",
    *            @OA\Schema(
    *                type="date"
    *            )
    *        ),
    *      @OA\Parameter(
    *            in="path", 
    *            required=true, 
    *            name="endDate", 
    *            description="format: HHHH-mm-dd",
    *            @OA\Schema(
    *                type="date"
    *            )
    *        ),
    *      @OA\Response(
    *            response=200, 
    *            description="Successful request"
    *        ),
    * )
 */
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
        $sumAll = $sumIncome+$sumExpense;

        return $sumAll;
    }

/**
 * @OA\Post(
 *     path="api/attachTags",
 *     tags={"Tag"},
 *     summary="Attach tag to invoice",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={
 *                      "invoice_id",
 *                      "tag_id"
 *                 },
 *                 @OA\Property(
 *                     property="invoice_id",
 *                     type="integer",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="tag_id",
 *                     type="array",
 *                     collectionFormat="multi",
 *                     @OA\Items(
 *                          type="integer", 
 *                          format="id"
 *                     ),
 *                     description="[tag_id1, tag_id2...]"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string"
 *                 ),
 *                    @OA\Property(
 *                     property="cost",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="enum: task, income, expense",
 *                 ),
 *                 @OA\Property(
 *                     property="deadline",
 *                     type="date",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     description="enum: active, completed, aborted"
 *                 ),
 *                 @OA\Property(
 *                     property="updated_at",
 *                     type="date",
 *                     description=""
 *                 ),
 *                 @OA\Property(
 *                     property="created_at",
 *                     type="date",
 *                     description=""
 *                 )
 *             )
 *         ),
 *         response=200,
 *         description="Invoice",
 *         )
 *     )
 * )
 */
    public function attachTags(AttachTagRequest $request){
        $invoiceId = $request->input('invoice_id');
        $tagsId = $request->input('tag_id');

        $targetInvoice = Invoice::findOrFail($invoiceId);
        $targetInvoice->tags()->sync($tagsId);

        $targetInvoice->load('tags');

        return $targetInvoice;
    }
}
