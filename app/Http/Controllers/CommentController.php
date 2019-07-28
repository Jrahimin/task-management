<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function addStore(Request $request,$parent)
    {
        $rules =[
            'comment'=>'required',
            'type'=>'required|integer',
            'parent_id'=>'required|integer',
        ];

        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()], 406);
        }
        // should send type of comment parent as hidden field in form
        $request['parent_id']=$parent;
        $comment=Comment::create($request->all());
        return response()->json($comment);
    }

    public function editStore(Request $request,Comment $comment)
    {
        $rules =[
            'comment'=>'required',
        ];

        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()], 406);
        }
        $comment->update($request->all());
        return response()->json($comment);
    }

    public function delete(Request $request,Comment $comment)
    {

      $comment->delete();
      return response()->json($comment);
    }

}
