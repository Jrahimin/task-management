<?php

namespace App\Http\Controllers;

use App\Model\TaxCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxCodeController extends Controller
{
    public function addStore(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'rate'=>'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        if($request->is_default)
            $request['is_default'] = 1;
        else
            $request['is_default'] = 0;

        $taxCode = TaxCode::create($request->all());
        return response()->json($taxCode);
    }

    public function editStore(Request $request, TaxCode $taxCode)
    {
        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'rate'=>'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->first()], 406);
        }

        $taxCode->update($request->all());
        return response()->json($taxCode);
    }

    public function all()
    {
        $taxcodes = TaxCode::all();
        return response()->json($taxcodes);
    }

    public function getTax(Request $request)
    {
        $id = $request->input('id');
        $taxCode = TaxCode::find($id);

        return response()->json($taxCode);
    }
}
