@extends('layouts.admin')
@section('page-title'){{ $lesson->exists ? 'Edit Lesson' : 'New Lesson' }}@stop
@section('content')
<div id="content-container">
    <div class="admin-page-header">
        <h2 class="admin-page-title">{{ $lesson->exists ? 'Edit Lesson' : 'New Lesson' }}</h2>
        <div class="admin-page-header__actions">
            <a href="{{ route('admin.course.lesson.index', $module->id) }}" class="admin-btn admin-btn--outline">&larr; Back to Lessons</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ $lesson->exists ? route('admin.course.lesson.update', $lesson->id) : route('admin.course.lesson.store', $module->id) }}" method="POST">
        @csrf
        @if ($lesson->exists) @method('PUT') @endif
        <div class="form-group">
            <label>Title / Heading</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $lesson->title) }}">
        </div>
        <div class="form-group">
            <label>Body Text</label>
            <textarea name="body" class="form-control" rows="6">{{ old('body', $lesson->body) }}</textarea>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="type" class="form-control">
                @foreach (['title', 'text', 'image', 'mixed', 'video'] as $t)
                    <option value="{{ $t }}" {{ old('type', $lesson->type ?? 'text') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        @if ($lesson->exists)
        <div class="form-group">
            <label>Order</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $lesson->order) }}">
        </div>
        @endif
        <button type="submit" class="admin-btn admin-btn--primary">Save</button>
    </form>

    @if ($lesson->exists)
        <hr>
        <h3>Images (one per language)</h3>
        <table class="table table-bordered">
            <thead><tr><th>Language</th><th>Preview</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                @forelse ($images as $img)
                    <tr>
                        <td>{{ strtoupper($img->language) }}</td>
                        <td><img src="{{ asset($img->path) }}" style="max-height:80px"></td>
                        <td class="text-center">
                            <form action="{{ route('admin.course.lesson.image.destroy', $img->id) }}" method="POST" onsubmit="return confirm('Remove this image?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn--outline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No images yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <form action="{{ route('admin.course.lesson.image.store', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="form-inline">
            @csrf
            <select name="language" class="form-control mr-2" required>
                <option value="en">English</option>
                <option value="es">Spanish</option>
                <option value="fr">French</option>
            </select>
            <input type="file" name="image" class="form-control mr-2" accept="image/*" required>
            <button type="submit" class="admin-btn admin-btn--primary">Upload Image</button>
        </form>

        <hr>
        <h3>Videos</h3>
        <table class="table table-bordered">
            <thead><tr><th>Order</th><th>URL</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                @forelse ($videos as $v)
                    <tr>
                        <td>{{ $v->order }}</td>
                        <td>{{ $v->url }}</td>
                        <td class="text-center">
                            <form action="{{ route('admin.course.lesson.video.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Remove this video?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn--outline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No videos yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <form action="{{ route('admin.course.lesson.video.store', $lesson->id) }}" method="POST" class="form-inline">
            @csrf
            <input type="text" name="url" class="form-control mr-2" style="width:500px" placeholder="Bunny Stream embed link (or full embed code), or a local path like /figma/xxx.mp4" required>
            <button type="submit" class="admin-btn admin-btn--primary">+ Add Video</button>
        </form>
        <small class="text-muted d-block mt-1">Paste the Bunny "Share &rarr; Embed" link or code directly — it's detected automatically and played with Bunny's real player and captions. A local path plays as a plain video instead.</small>
    @endif
</div>
@endsection
