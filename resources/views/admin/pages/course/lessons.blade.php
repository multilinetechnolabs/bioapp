@extends('layouts.admin')
@section('page-title')Lessons: {{ $module->title }}@stop
@section('content')
<div id="content-container">
    <div class="admin-page-header">
        <h2 class="admin-page-title">Lessons: {{ $module->title }}</h2>
        <div class="admin-page-header__actions">
            <a href="{{ route('admin.course.edit', $module->course_id) }}" class="admin-btn admin-btn--outline">&larr; Back to Course</a>
            <a href="{{ route('admin.course.lesson.create', $module->id) }}" class="admin-btn admin-btn--primary">+ New Lesson</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead><tr><th>Order</th><th>Title</th><th class="text-center">Actions</th></tr></thead>
        <tbody>
            @forelse ($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->order }}</td>
                    <td>{{ $lesson->title ?: '(untitled)' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.course.lesson.edit', $lesson->id) }}" class="admin-btn admin-btn--outline">Edit</a>
                        <form action="{{ route('admin.course.lesson.destroy', $lesson->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this lesson?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="admin-btn admin-btn--outline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No lessons yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
