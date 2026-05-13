<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;
use DB;
use DataTables;

use App\Models\Playlist;
use App\Models\Media;

class PlaylistController extends BaseController
{
    public function index()
    {
        return view('admin.pages.playlist.index');
    }

    public function update(Request $request, $id)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->update($request->all());
        return redirect()->to('/admin/playlist')->with('message.success', 'You have successfully updated playlist details.');
    }

    public function destroy($id)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->delete();
        return redirect()->to('/admin/playlist')->with('message.success', 'You have successfully deleted playlist.');
    }

    public function datatables()
    {
        $playlists = Playlist::query();

        return DataTables::eloquent($playlists)
            ->addColumn('action', function ($playlist) {
                return "
                    <a href='javascript:void(0)' class='fa fa-headphones fa-2x' onClick='listenPlaylist(".$playlist->id.", \"".$playlist->name."\")' title='Listen to Playlist'></a>&nbsp;&nbsp;
                    <a href='".url('/admin/playlist/allmedia/'.$playlist->id)."' class='fa fa-music fa-2x' title='Add Media to Playlist'></a>&nbsp;&nbsp;
                    <a data-target='#editModal' data-toggle='modal' href='javascript:void(0)' class='fa fa-edit fa-2x' onClick='editPlaylist(".$playlist->id.")' title='Edit Playlist'></a>&nbsp;&nbsp;
                    <a data-target='#deleteModal' data-toggle='modal' href='javascript:void(0)' class='fa fa-trash-o fa-2x' onClick='deletePlaylist(".$playlist->id.")' title='Remove Playlist'></a>
                ";
            })->toJson();
    }

    public function store()
    {
        $playlist = Playlist::create([
            'name'          => $_POST['name'],
            'description'   => $_POST['description'],
            'user_id'       => Auth::user()->id
        ]);
        return redirect()->to('/admin/playlist')->with('message.success', 'You have successfully added playlist. See list below for your data.');
    }

    public function getMedia($playlist_id)
    {
        $mediaArr = [];

        if (! empty($playlist_id)) {
            $playlist = Playlist::with('medias')->findOrFail($playlist_id);

            foreach ($playlist->medias as $media) {
                $fileUrl = $media->file_url;

                if (empty($fileUrl)) {
                    continue;
                }

                $mediaArr[] = [
                    'title' => $media->file_name,
                    'mp3' => $fileUrl,
                ];
            }
        }

        return response()->json($mediaArr, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function allMedia($playlist_id)
    {
        $all_media      = array();
        $assigned_media = array();

        $allmedias = Media::All()->toArray();
        if (count($allmedias) > 0) {
            foreach ($allmedias as $media) {
                $all_media[$media['id']] = $media['file_name'];
            }
        }

        $playlistmedia = DB::select("SELECT media.* 
                                    FROM media_playlists
                                    JOIN media ON media_playlists.media_id = media.id
                                    WHERE media_playlists.playlist_id = ". $playlist_id);
                                    
        if (count($playlistmedia) > 0) {
            foreach ($playlistmedia as $pmedia) {
                $assigned_media[$pmedia->id] = $pmedia->file_name;
                if (array_key_exists($pmedia->id, $all_media)) {
                    unset($all_media[$pmedia->id]);
                }
            }
        }
        return view('admin.pages.playlist.allmedia', compact('playlist_id', 'all_media', 'assigned_media'));
    }
}
