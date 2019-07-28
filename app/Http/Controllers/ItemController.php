<?php

namespace App\Http\Controllers;

use App\Model\Equipment;
use App\Model\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function addStore(Request $request)
    {
        //return response()->json($request->all());
        $validation = Validator::make($request->all(),[
            'type'=>'required|integer',
            'name'=>'required',
            'price'=>'required|numeric',
            'cost'=>'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $item = Item::create($request->all());
        return response()->json($item);
    }

    public function editStore(Request $request, Item $item)
    {
        $validation = Validator::make($request->all(),[
            'type'=>'required|integer',
            'name'=>'required',
            'price'=>'required|numeric',
            'cost'=>'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $item->update($request->all());

        return response()->json($item);
    }

    public function viewAll()
    {
        $items=Item::all();
        return response()->json($items);
    }


    public function viewTypeAll($type)
    {
        $items=Item::where('type',$type)->get();
        return response()->json($items);
    }
    public function assignItem(Request $request)
    {
        $item=Item::find($request->item_id);
        if($request->assignment_type=='workorder')
        {
            $item->work_orders()->attach($request->work_order_id,['quantity'=>$request->quantity,
                'price'=>$request->price,
                'cost'=>$request->cost,
                'description'=>$request->description,
                'date'=>$request->date,
                'comment'=>$request->comment,
                'billing_status'=>$request->billing_status,
                'tax_status'=>$request->tax_status]);
        }
        else{
            $item->recurring_workorders()->attach($request->work_order_id,['quantity'=>$request->quantity,
                'price'=>$request->price,
                'cost'=>$request->cost,
                'description'=>$request->description,
                'date'=>$request->date,
                'comment'=>$request->comment,
                'billing_status'=>$request->billing_status,
                'tax_status'=>$request->tax_status]);

        }

    }

    public function viewItem($id)
    {
        $item=Item::find($id);
        return response()->json($item);
    }


    public function deleteItem(Item $item)
    {
        $item->work_orders()->detach();
        $item->recurring_workOrders()->detach();
        $item->delete();
        return response()->json($item);
    }



}
