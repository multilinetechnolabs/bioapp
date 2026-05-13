<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;

use Auth;
use Validator;
use Storage;
use App\Models\Client;
use App\Models\User;

class DataCacheController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('subscriber')->except('info');
    }

    public function index()
    {
        return view('app.pages.data_cache.index');
    }

    public function info()
    {
        return view('app.pages.data_cache.info');
    }

    public function client_info()
    {
        return view('app.pages.data_cache.client_info');
    }

    public function client_show($id)
    {
        $client = Client::find($id);

        if (empty($client) && ($client->user_id != Auth::user()->id || !Auth::user()->isAdmin())) {
            return redirect('/data_cache')->with('message.fail', 'Access Denied. You do not have permissions to do that.');
            ;
        } else {
            return view('app.pages.data_cache.client.show', compact('client'));
        }
    }

    public function client_bio($id)
    {
        $client = Client::find($id);

        if (empty($client) && ($client->user_id != Auth::user()->id || !Auth::user()->isAdmin())) {
            return redirect('/data_cache')->with('message.fail', 'Access Denied. You do not have permissions to do that.');
            ;
        } else {
            return view('app.pages.data_cache.client.body_scan', compact('client'));
        }
    }

    public function client_chakra($id)
    {
        $client = Client::find($id);

        if (empty($client) && ($client->user_id != Auth::user()->id || !Auth::user()->isAdmin())) {
            return redirect('/data_cache')->with('message.fail', 'Access Denied. You do not have permissions to do that.');
            ;
        } else {
            return view('app.pages.data_cache.client.chakra_scan', compact('client'));
        }
    }

    public function add_pairs($id, Request $request)
    {
        $client = Client::find($id);

        if (empty($client) && ($client->user_id != Auth::user()->id || !Auth::user()->isAdmin())) {
            return redirect('/data_cache')->with('message.fail', 'Access Denied. You do not have permissions to do that.');
            ;
        } else {
            return view('app.pages.data_cache.client.list_pairs', compact('client', 'request'));
        }
    }

    public function bio()
    {
        return view('app.pages.data_cache.bio');
    }

    public function chakra()
    {
        return view('app.pages.data_cache.chakra');
    }

    public function preferences()
    {
        return view('app.pages.data_cache.preferences');
    }

    public function help()
    {
        return view('app.pages.data_cache.help');
    }

    public function updatePreferences(Request $request)
    {
        if ($request->ajax()) {
            $postdate = $request->all();
            $messages = [
                'required' => '* Required',
                'min' => '* Required',
            ];
            
            $v = Validator::make($postdate, User::rules(), $messages);
                        
            if ($v->fails()) {
                return response()->json(array("error" => $v->messages(), "message" => 'Check error notifications', "status" => 'error'), 200);
            } else {
                $edtinfo = User::find($postdate['id']);
                $edtinfo->update($postdate);
                return response()->json(array("error" => '', "message" => 'Update Successfully', "status" => 'success'), 200);
            }
        } else {
            return response()->json(array("error" => '', "message" => 'No ajax', "status" => 'success'), 200);
        }
    }

    public function uploadLogo()
    {
        if (!empty($_FILES['logo_file']['tmp_name'])) {
            $filename   = $_FILES['logo_file']['name'];
            $s3_name    = time()."_".$_FILES['logo_file']['name'];
            $filePath   = '/users/uid-'.Auth::user()->id.'/logos/'.$s3_name;
            try {
                Storage::put($filePath, fopen($_FILES['logo_file']['tmp_name'], 'r+'), 'public');
            } catch (\Exception $e) {
                return redirect()->to('/data_cache')->with('message.fail', 'Error in uploading file. Please try again.');
            }

            $logo = \App\Models\Logo::create([
                'user_id'       => Auth::user()->id,
                'file_name'     => $filename,
                's3_name'       => $s3_name
            ]);

            $user = Auth::user();
            $user->logo = $s3_name;
            $user->save();
            
            return redirect()->to('/data_cache')->with('message.success', 'You have successfully uploaded a logo file.');
        } else {
            return redirect()->to('/data_cache')->with('message.fail', 'Error in uploading file. Please try again.');
        }
    }

    public function uploadConsentForm(Request $request)
    {
        $client = Client::find($request->client_id);
        $description = $request->description;
        $date_entered = $request->date_entered;

        if ($client->user_id != Auth::user()->id) {
            return redirect()->to('/data_cache/clients/'.$client->id)->with('message.fail', 'S3 Error in uploading consent form file. Please try again.');
        }

        if (!empty($_FILES['consent_form_file']['tmp_name'])) {
            $filename   = $_FILES['consent_form_file']['name'];
            $s3_name    = time()."_".$_FILES['consent_form_file']['name'];
            $filePath   = '/clients/uid-'.$client->id.'/consent_forms/'.$s3_name;

            try {
                Storage::put($filePath, fopen($_FILES['consent_form_file']['tmp_name'], 'r+'), 'public');
            } catch (\Exception $e) {
                return redirect()->to('/data_cache/clients/'.$client->id)->with('message.fail', 'Error in uploading consent form file. Please try again.');
            }

            $consent_form = \App\Models\ConsentForm::create([
                'client_id'     => $client->id,
                'date_entered'  => $date_entered,
                'file_name'     => $filename,
                's3_name'       => $s3_name,
                'description'   => $description
            ]);
            
            return redirect()->to('/data_cache/clients/'.$client->id)->with('message.success', 'You have successfully uploaded a consent form.');
        } else {
            return redirect()->to('/data_cache/clients/'.$client->id)->with('message.fail', 'Error in uploading file. Please try again.');
        }
    }
}
