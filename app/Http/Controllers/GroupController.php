<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
use URL;

use Auth;

use App\Models\GroupDiscussion;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('subscriber');
    }

    public function save_discussions_database(Request $request)
    {
        if ($request->ajax()) {
            $postdata = $request->all();
            $messages = [
                'required' => '* Required',
                'min' => '* Required',
            ];
            
            $v = Validator::make($postdata, GroupDiscussion::rules(), $messages);
                        
            if ($v->fails()) {
                return response()->json(array("error" => $v->messages(), "message" => 'Check error notifications', "status" => 'error'), 200);
            } else {
                $postdata['user_id'] = Auth::user()->id;
                GroupDiscussion::create($postdata);
                return response()->json(array("error" => '', "message" => 'Share Successfully', "status" => 'success'), 200);
            }
        } else {
            return response()->json(array("error" => '', "message" => 'No ajax', "status" => 'success'), 200);
        }
    }
}
