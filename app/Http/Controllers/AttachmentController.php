<?php

namespace App\Http\Controllers;

use App\Model\Attachment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{

    public function addStore(Request $request,$parent)
    {
        $rules =[
            'file'=>'required_without:link_url,',
            'link_url'=>'required_without:file',
            'description'=>'required',
            'visibility'=>'required',
            'type'=>'required|integer',
            'parent_id'=>'required|integer'
               ];

        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()], 406);
        }
        //should sent an object as parent from view

        //getting the file & renaming
        $file = $request->file('file');
        $fileName = time() . $file->getClientOriginalName();

        //moving file to public/image folder with new name
        $file->move('image', $fileName);
        $request['file_path']=$fileName;
        $request['parent_id']=$parent;
        $attachment=Attachment::create($request->all());

    }

    public function editStore(Request $request,Attachment $attachment)
    {
        $rules =[
            'description'=>'required',
            'visibility'=>'required',
        ];

        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()], 406);
        }
        $attachment->update($request->all());

    }
    public function delete(Request $request,Attachment $attachment)
    {
        $attachment->delete();
    }

}
