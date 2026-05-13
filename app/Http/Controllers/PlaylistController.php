<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use DataTables;

use App\Models\Playlist;
use App\Models\Media;

class PlaylistController extends Controller
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

    public function index()
    {
        if (Auth::user()->isPractitioner() || Auth::user()->isAdmin()) {
            return view('app.pages.playlist.index');
        } else {
            return redirect()->to('/dashboard');
        }
    }

    public function show($id)
    {
        return Playlist::find($id);
    }
    
    public function update(Request $request, $id)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->update($request->all());
        return redirect()->to('/playlist')->with('message.success', 'You have successfully updated playlist details.');
    }

    public function destroy($id)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->delete();
        return redirect()->to('/playlist')->with('message.success', 'You have successfully deleted playlist.');
    }
    
    public function datatables()
    {
        $playlists = Playlist::query();

        return DataTables::eloquent($playlists)
            ->addColumn('action', function ($playlist) {
                $id   = $playlist->id;
                $name = addslashes($playlist->name);
                $desc = addslashes($playlist->description ?? '');
                $allMediaUrl = url('/playlist/allmedia/' . $id);

                $icPlay  = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 18v-6a9 9 0 0118 0v6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3v5zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3v5z"/></svg>';
                $icMusic = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>';
                $icEdit  = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                $icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

                $btnPlay  = "<button class='dt-action-btn dt-action-btn--teal' onclick='listenPlaylist({$id}, \"{$name}\")'>{$icPlay} Listen</button>";
                $btnMusic = "<a class='dt-action-btn dt-action-btn--slate' href='{$allMediaUrl}'>{$icMusic} Media</a>";
                $btnEdit  = "<button class='dt-action-btn dt-action-btn--slate' data-target='#editModal' data-toggle='modal' onclick='editPlaylist({$id}, \"{$name}\", \"{$desc}\")'>{$icEdit} Edit</button>";
                $btnTrash = "<button class='dt-action-btn dt-action-btn--red' data-target='#deleteModal' data-toggle='modal' onclick='deletePlaylist({$id})'>{$icTrash} Remove</button>";

                if (Auth::user()->id == $playlist->user_id) {
                    return "<div class='dt-action-group'>{$btnPlay}{$btnMusic}{$btnEdit}{$btnTrash}</div>";
                } else {
                    return "<div class='dt-action-group'>{$btnPlay}</div>";
                }
            })->toJson();
    }

    public function store()
    {
        $playlist = Playlist::create([
            'name'          => $_POST['name'],
            'description'   => $_POST['description'],
            'user_id'       => Auth::user()->id
        ]);
        return redirect()->to('/playlist')->with('message.success', 'You have successfully added playlist. See list below for your data.');
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
        return view('app.pages.playlist.allmedia', compact('playlist_id', 'all_media', 'assigned_media'));
    }
}
