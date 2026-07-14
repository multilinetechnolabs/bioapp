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

    <style>
        .admin-collapsible { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 1.5rem; overflow: hidden; }
        .admin-collapsible__title { cursor: pointer; list-style: none; padding: 0.9rem 1.1rem; font-size: 1.05rem; font-weight: 700; color: #0f172a; background: #f8fafc; }
        .admin-collapsible__title::-webkit-details-marker { display: none; }
        .admin-collapsible__title::before { content: '\25B8'; display: inline-block; margin-right: 0.5rem; transition: transform .15s ease; }
        .admin-collapsible[open] > .admin-collapsible__title::before { transform: rotate(90deg); }
        .admin-collapsible__body { padding: 1.25rem 1.1rem; }
    </style>

    <details class="admin-collapsible" open>
        <summary class="admin-collapsible__title">Title / Heading, Body Text, Lesson Order</summary>
        <div class="admin-collapsible__body">
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
                @if ($lesson->exists)
                <div class="form-group">
                    <label>Lesson Order</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $lesson->order) }}">
                </div>
                @endif
                <button type="submit" class="admin-btn admin-btn--primary">Save</button>
            </form>
        </div>
    </details>

    @if ($lesson->exists)
        <p class="text-muted">Title and body text always show. Add an image and/or a video below and it'll show on the course page too — add both and the lesson shows all of it.</p>

        <details class="admin-collapsible">
            <summary class="admin-collapsible__title">Images (one per language)</summary>
            <div class="admin-collapsible__body">
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
            </div>
        </details>

        <details class="admin-collapsible">
            <summary class="admin-collapsible__title">Videos</summary>
            <div class="admin-collapsible__body">
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
            </div>
        </details>
    @endif
</div>
@endsection
