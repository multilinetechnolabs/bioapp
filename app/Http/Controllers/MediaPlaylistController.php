<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\MediaPlaylist;

class MediaPlaylistController extends Controller
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

    public function store()
    {
        $url = url("/".$_POST['redirect_url']);
        if (isset($_POST['media_ids']) && is_array($_POST['media_ids'])) {
            if (count($_POST['media_ids']) > 0) {
                $del = DB::delete('DELETE FROM media_playlists WHERE playlist_id = '. $_POST['playlist_id']);
                foreach ($_POST['media_ids'] as $media_id) {
                    $mediaplaylist = MediaPlaylist::create([
                        'media_id'      => $media_id,
                        'playlist_id'   => $_POST['playlist_id']
                    ]);
                }
            }
        } else {
            if (isset($_POST['media_id']) && !empty($_POST['media_id'])) {
                $mediaplaylist = MediaPlaylist::create([
                    'media_id'      => $_POST['media_id'],
                    'playlist_id'   => $_POST['playlist_id']
                ]);
            }
        }

        return redirect()->to($url)->with('message.success', 'You have successfully added media to playlist.');
    }
}
