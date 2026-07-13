@extends('layouts.admin')
@section('page-title')Course Config@stop
@section('content')
<div id="content-container">
    <div class="admin-page-header">
        <h2 class="admin-page-title">Course Config</h2>
        <div class="admin-page-header__actions">
            <a href="{{ route('admin.course.create') }}" class="admin-btn admin-btn--primary">+ New Course</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="admin-dt-wrap table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Active</th>
                    <th>Modules</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>${{ number_format($course->price, 2) }}</td>
                        <td>{{ $course->is_active ? 'Yes' : 'No' }}</td>
                        <td>{{ $course->modules()->count() }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.course.edit', $course->id) }}" class="admin-btn admin-btn--outline">Manage</a>
                            <form action="{{ route('admin.course.destroy', $course->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this course and all its modules/lessons?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn--outline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No courses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
