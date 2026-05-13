<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Contracts\Filesystem\Filesystem;

use Auth;
use DataTables;
use Storage;

use App\Models\Media;
use App\Models\Playlist;
use App\Models\MediaPlaylist;

class MediaController extends BaseController
{
    public function index()
    {
        $playlists = Playlist::all()->toArray();
        return view('admin.pages.media.index', compact('playlists'));
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $media->update($request->all());
        return redirect()->to('/admin/media')->with('message.success', 'You have successfully updated media details.');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return redirect()->to('/admin/media')->with('message.success', 'You have successfully deleted media file.');
    }

    public function datatables()
    {
        $medias = Media::query()->select(['id', 'file_name', 'description', 'user_id']);
        return DataTables::eloquent($medias)
            ->addColumn('action', function ($media) {
                $media->setAppends([]);

                return "
                    <a data-target='#playerModal' data-toggle='modal' href='javascript:void(0)' class='fa fa-headphones fa-2x' onClick='playMedia(".$media->id.", \"".$media->file_name."\")' title='Play Media'></a>&nbsp;&nbsp;
                    <a data-target='#comsModal' data-toggle='modal' href='javascript:void(0)' class='fa fa-music fa-2x' onClick='addToPlaylist(".$media->id.", \"".$media->file_name."\", \"admin/media\")' title='Add to Playlist'></a>&nbsp;&nbsp;
                    <a data-target='#editModal' data-toggle='modal' href='javascript:void(0)' class='fa fa-edit fa-2x' onClick='editMedia(".$media->id.")' title='Edit Media'></a>&nbsp;&nbsp;
                    <a data-target='#deleteModal' data-toggle='modal' href='javascript:void(0)' class='fa fa-trash-o fa-2x' onClick='deleteMedia(".$media->id.")' title='Remove Media'></a>
                ";
            })->toJson();
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
                return redirect()->to('/admin/media')->with('message.fail', 'Error in uploading file. Please try again.');
            }

            $media = Media::create([
                'file_name'     => $filename,
                's3_name'       => $s3_name,
                'description'   => $_POST['description'],
                'user_id'       => Auth::user()->id
            ]);
            return redirect()->to('/admin/media')->with('message.success', 'You have successfully uploaded a media file. See list below for your data.');
        } else {
            return redirect()->to('/admin/media')->with('message.fail', 'Error in uploading file. Please try again.');
        }
    }
}
