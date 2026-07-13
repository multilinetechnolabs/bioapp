@extends('layouts.admin')
@section('page-title'){{ $course->exists ? 'Edit Course' : 'New Course' }}@stop
@section('content')
<div id="content-container">
    <div class="admin-page-header">
        <h2 class="admin-page-title">{{ $course->exists ? 'Edit Course' : 'New Course' }}</h2>
        <div class="admin-page-header__actions">
            <a href="{{ route('admin.course.index') }}" class="admin-btn admin-btn--outline">&larr; Back</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ $course->exists ? route('admin.course.update', $course->id) : route('admin.course.store') }}" method="POST">
        @csrf
        @if ($course->exists) @method('PUT') @endif
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $course->description) }}</textarea>
        </div>
        <div class="form-group">
            <label>Price ($)</label>
            @if ($course->exists)
                <input type="text" class="form-control" value="${{ number_format($course->price, 2) }}" disabled>
                <small class="form-text text-muted">Price is controlled by the Freemius plan and can only be changed there — not editable here.</small>
            @else
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', 0) }}" required>
                <small class="form-text text-muted">Set this to match the Freemius plan price. It cannot be changed here after the course is created.</small>
            @endif
        </div>
        <div class="form-group">
            <label>Order</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $course->order ?? 0) }}">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $course->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">Active (visible to users)</label>
        </div>
        <button type="submit" class="admin-btn admin-btn--primary">Save</button>
    </form>

    @if ($course->exists)
        <hr>
        <h3>Modules</h3>
        <table class="table table-bordered">
            <thead><tr><th>Order</th><th>Title</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                @forelse ($modules as $m)
                    <tr>
                        <form action="{{ route('admin.course.module.update', $m->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td><input type="number" name="order" value="{{ $m->order }}" class="form-control" style="width:80px"></td>
                            <td><input type="text" name="title" value="{{ $m->title }}" class="form-control"></td>
                            <td class="text-center">
                                <button type="submit" class="admin-btn admin-btn--outline">Save</button>
                                <a href="{{ route('admin.course.lesson.index', $m->id) }}" class="admin-btn admin-btn--outline">Lessons</a>
                        </form>
                                <form action="{{ route('admin.course.module.destroy', $m->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this module and its lessons?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--outline">Delete</button>
                                </form>
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No modules yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        <form action="{{ route('admin.course.module.store', $course->id) }}" method="POST" class="form-inline">
            @csrf
            <input type="text" name="title" class="form-control mr-2" placeholder="New module title" required>
            <button type="submit" class="admin-btn admin-btn--primary">+ Add Module</button>
        </form>
    @endif
</div>
@endsection
