<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Auth;
use DB;
use Storage;
use App\Models\Media;
use App\Models\Playlist;
use App\Models\MediaPlaylist;
use DataTables;
use Carbon\Carbon;

class MediaController extends Controller
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
        $playlists = Playlist::all()->toArray();
        return view('app.pages.media.index', compact('playlists'));
    }

    public function show($id)
    {
        $media = Media::find($id);

        if ($media) {
            $media->append('file_url');
        }

        return $media;
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $media->update($request->all());
        return redirect()->to('/media')->with('message.success', 'You have successfully updated media details.');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return redirect()->to('/media')->with('message.success', 'You have successfully deleted media file.');
    }

    public function datatables()
    {
        $medias = Media::query()->select(['id', 'file_name', 'description', 'user_id']);

        return DataTables::eloquent($medias)
            ->addColumn('action', function ($media) {
                $media->setAppends([]);

                $id   = $media->id;
                $name = addslashes($media->file_name);
                $desc = addslashes($media->description ?? '');

                $icPlay  = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 18v-6a9 9 0 0118 0v6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3v5zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3v5z"/></svg>';
                $icMusic = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>';
                $icEdit  = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                $icTrash = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';

                $btnPlay  = "<button class='dt-action-btn dt-action-btn--teal' data-target='#playerModal' data-toggle='modal' onclick='playMedia({$id}, \"{$name}\")'>{$icPlay} Play</button>";
                $btnMusic = "<button class='dt-action-btn dt-action-btn--slate' data-target='#comsModal' data-toggle='modal' onclick='addToPlaylist({$id}, \"{$name}\", \"media\")'>{$icMusic} Playlist</button>";
                $btnEdit  = "<button class='dt-action-btn dt-action-btn--slate' data-target='#editModal' data-toggle='modal' onclick='editMedia({$id}, \"{$name}\", \"{$desc}\")'>{$icEdit} Edit</button>";
                $btnTrash = "<button class='dt-action-btn dt-action-btn--red' data-target='#deleteModal' data-toggle='modal' onclick='deleteMedia({$id})'>{$icTrash} Remove</button>";

                if (Auth::user()->isPractitioner()) {
                    if (Auth::user()->id == $media->user_id) {
                        return "<div class='dt-action-group'>{$btnPlay}{$btnMusic}{$btnEdit}{$btnTrash}</div>";
                    } else {
                        return "<div class='dt-action-group'>{$btnPlay}{$btnMusic}</div>";
                    }
                } else {
                    return "<div class='dt-action-group'>{$btnPlay}</div>";
                }
            })->toJson();
    }

    public function allmedia()
    {
        $mediaArr = [];
        $availablePaths = Storage::files('audio_files');
        $medialist = Media::query()
            ->select(['id', 'file_name', 's3_name'])
            ->get();

        foreach ($medialist as $media) {
            $partialUrl = $media->resolveAudioStoragePath($availablePaths);

            if (empty($partialUrl)) {
                continue;
            }

            $mediaArr[] = [
                'title' => $media->file_name,
                'mp3' => $media->audioUrlForPath($partialUrl),
            ];
        }

        shuffle($mediaArr);

        return response()
            ->json($mediaArr, 200, [], JSON_UNESCAPED_SLASHES)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function store()
    {
        if (!empty($_FILES['media_file']['tmp_name'])) {
            $filename   = $_FILES['media_file']['name'];
            $s3_name    = time()."_".$_FILES['media_file']['name'];
            $filePath   = '/audio_files/'.$s3_name;

            try {
                Storage::put($filePath, fopen($_FILES['media_file']['tmp_name'], 'r+'), 'public');
            } catch (\Exception $e) {
                return redirect()->to('/media')->with('message.fail', 'Error in uploading file. Please try again.');
            }
            
            $media = Media::create([
                'file_name'     => $filename,
                's3_name'       => $s3_name,
                'description'   => $_POST['description'],
                'user_id'       => Auth::user()->id
            ]);
            return redirect()->to('/media')->with('message.success', 'You have successfully uploaded a media file. See list below for your data.');
        } else {
            return redirect()->to('/media')->with('message.fail', 'Error in uploading file. Please try again.');
        }
    }
}
