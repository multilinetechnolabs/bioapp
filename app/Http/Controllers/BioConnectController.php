<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
use URL;
use Storage;

use Auth;
use Hash;

use App\Models\User;

class BioConnectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('subscriber')->except('index', 'groups', 'profile', 'updatePassword', 'info');
    }

    public function index()
    {
        return view('app.pages.bioconnect.friends');
    }

    public function info()
    {
        return view('app.pages.bioconnect.info');
    }

    public function profile()
    {
        $data['pro_info']= User::find(Auth::user()->id);
        return view('app.pages.bioconnect.profile', $data);
    }

    public function activities()
    {
        return view('app.pages.bioconnect.activities');
    }

    public function friends()
    {
        return view('app.pages.bioconnect.friends');
    }

    public function request()
    {
        return view('app.pages.bioconnect.friends_requests');
    }

    public function findFriend()
    {
        return view('app.pages.bioconnect.findfriends');
    }

    public function groups()
    {
        $data["posts"] = \App\Models\GroupDiscussion::where('dis_status', 1)->orderBy('created_at', 'DESC')->get();
        return view('app.pages.bioconnect.groups', $data);
    }

    public function groups_mydiscussion()
    {
        $data["posts"] = DB::table('group_discussions')->select('discussion')->where('dis_status', 1)->orderBy('created_at', 'DESC')->get();
        return view('app.pages.bioconnect.groups_mydiscussion', $data);
    }

    public function groups_mycomment()
    {
        $data["posts"] = DB::table('group_discussions')->select('discussion')->where('dis_status', 1)->orderBy('created_at', 'DESC')->get();
        return view('app.pages.bioconnect.groups_mycomment', $data);
    }

    public function groups_mostcomments()
    {
        $data["posts"] = DB::table('group_discussions')->select('discussion')->where('dis_status', 1)->orderBy('created_at', 'DESC')->get();
        return view('app.pages.bioconnect.groups_mostcomments', $data);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!empty($request->name)) {
            $user->name = $request->name;
        }
        if (!empty($request->email)) {
            $user->email = $request->email;
        }
        if (!empty($request->business)) {
            $user->business = $request->business;
        }
        if (!empty($request->location)) {
            $user->location = $request->location;
        }
        if (!empty($request->privacy) && $request->privacy == 'on') {
            $user->privacy = true;
        } else {
            $user->privacy = false;
        }

        $user->save();

        return redirect()->to('/bioconnect/profile');
    }

    public function saveprofile_database(Request $request)
    {
        $postdate = $request->all();
        $user_id = $postdate['user_id'];

        if ($request->input('togBtn')) {
            $postdate['privacy'] = 1;
        } else {
            $postdate['privacy'] = 0;
        }

        if (!empty($_FILES['upload']['tmp_name'])) {
            $filename   = $_FILES['upload']['name'];
            $s3_name    = time()."_".$_FILES['upload']['name'];
            $filePath   = '/users/uid-'.$user_id.'/profile_pictures/'.$s3_name;

            try {
                Storage::put($filePath, fopen($_FILES['upload']['tmp_name'], 'r+'), 'public');
            } catch (\Exception $e) {
                return redirect()->to('/bioconnect/profile')->with('message.fail', 'Error in uploading file. Please try again.');
            }

            $postdate['profile_picture'] = $s3_name;
        }

        $user = User::find($user_id);
        $mirrorReference = [
            'name' => $user->name,
            'business' => $user->business,
        ];

        $user->update($postdate);

        if (empty($user->getErrors()->all())) {
            $user->refresh();
            $this->syncMirroredProfiles($user, $mirrorReference);

            return redirect()->to('/bioconnect/profile')->with('message.success', 'You have successfully saved your profile.');
        } else {
            return redirect('/bioconnect/profile')->with('message.fail', 'Unable to save your profile, please check your inputs in the form below.')->withErrors($user->getErrors());
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'          => 'required',
            'new_password'              => 'required|min:8|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'new_password.different'          => 'New password must be different from your current password. / La nueva contraseña debe ser diferente a la actual.',
            'new_password.min'                => 'New password must be at least 8 characters. / La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password_confirmation.same'  => 'New password and confirmation do not match. / La nueva contraseña y la confirmación no coinciden.',
        ]);

        if ($validator->fails()) {
            return redirect()->to('/bioconnect/profile#change-password')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->to('/bioconnect/profile#change-password')
                ->withErrors(['current_password' => 'The current password is incorrect. / La contraseña actual es incorrecta.'])
                ->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->to('/bioconnect/profile#change-password')->with('password.success', 'Password updated successfully. / Contraseña actualizada correctamente.');
    }

    protected function syncMirroredProfiles(User $user, array $mirrorReference = [])
    {
        $mirrorIds = User::query()
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($user, $mirrorReference) {
                $this->applyMirrorIdentity($query, $mirrorReference['name'] ?? null, $mirrorReference['business'] ?? null);

                if (($mirrorReference['name'] ?? null) !== $user->name || ($mirrorReference['business'] ?? null) !== $user->business) {
                    $query->orWhere(function ($nestedQuery) use ($user) {
                        $this->applyMirrorIdentity($nestedQuery, $user->name, $user->business);
                    });
                }
            })
            ->where(function ($query) use ($user) {
                if ($user->isAdmin()) {
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->whereIn('name', ['practitioner', 'therapist']);
                    });
                } else {
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'administrator');
                    });
                }
            })
            ->pluck('id');

        if ($mirrorIds->isEmpty()) {
            return;
        }

        DB::table('users')
            ->whereIn('id', $mirrorIds)
            ->update([
                'name' => $user->name,
                'business' => $user->business,
                'location' => $user->location,
                'address' => $user->address,
                'country' => $user->country,
                'zip' => $user->zip,
                'age' => $user->age,
                'privacy' => $user->privacy,
                'profile_picture' => $user->profile_picture,
                'updated_at' => now(),
            ]);
    }

    protected function applyMirrorIdentity($query, $name, $business)
    {
        $query->where('name', '=', $name);

        if (is_null($business)) {
            $query->whereNull('business');
        } else {
            $query->where('business', '=', $business);
        }
    }
}
