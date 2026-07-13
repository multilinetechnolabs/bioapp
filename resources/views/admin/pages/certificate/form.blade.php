@extends('layouts.admin')
@section('page-title')Certificate Template@stop
@section('content')
<div id="content-container">
    <div class="admin-page-header">
        <h2 class="admin-page-title">Certificate &amp; Badge Template</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="alert alert-info" style="font-size:.85rem;">
        <strong>Placeholders</strong> you can use in any text field below — they are replaced automatically for each learner:
        <code>{name}</code> learner's name &middot;
        <code>{course}</code> course title &middot;
        <code>{lessons}</code> total lesson count &middot;
        <code>{date}</code> completion date &middot;
        <code>{issuer}</code> issuer name.
    </div>

    <form action="{{ route('admin.certificate.update') }}" method="POST">
        @csrf
        @method('PUT')

        <h3>Certificate</h3>
        <div class="form-group">
            <label>Eyebrow (small heading above the title)</label>
            <input type="text" name="cert_eyebrow" class="form-control" value="{{ old('cert_eyebrow', $template->cert_eyebrow) }}">
        </div>
        <div class="form-group">
            <label>Title (use a new line to split into two lines)</label>
            <textarea name="cert_title" class="form-control" rows="2">{{ old('cert_title', $template->cert_title) }}</textarea>
        </div>
        <div class="form-group">
            <label>Intro line (above the learner's name)</label>
            <input type="text" name="cert_intro" class="form-control" value="{{ old('cert_intro', $template->cert_intro) }}">
        </div>
        <div class="form-group">
            <label>Body text</label>
            <textarea name="cert_body" class="form-control" rows="3">{{ old('cert_body', $template->cert_body) }}</textarea>
        </div>
        <div class="form-group">
            <label>Disclaimer text</label>
            <textarea name="cert_disclaimer" class="form-control" rows="3">{{ old('cert_disclaimer', $template->cert_disclaimer) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Issuer name</label>
                <input type="text" name="issuer_name" class="form-control" value="{{ old('issuer_name', $template->issuer_name) }}">
            </div>
            <div class="col-md-4 form-group">
                <label>Issuer email</label>
                <input type="email" name="issuer_email" class="form-control" value="{{ old('issuer_email', $template->issuer_email) }}">
            </div>
            <div class="col-md-4 form-group">
                <label>Accent color</label>
                <input type="color" name="accent_color" class="form-control" style="max-width:80px;height:38px;" value="{{ old('accent_color', $template->accent_color) }}">
            </div>
        </div>

        <hr>
        <h3>Digital Badge</h3>
        <div class="form-group form-check">
            <input type="checkbox" name="badge_enabled" class="form-check-input" value="1" {{ old('badge_enabled', $template->badge_enabled) ? 'checked' : '' }}>
            <label class="form-check-label">Show the digital badge on the certificate page</label>
        </div>
        <div class="form-group">
            <label>Badge label (short word inside the badge circle)</label>
            <input type="text" name="badge_label" class="form-control" value="{{ old('badge_label', $template->badge_label) }}">
        </div>
        <div class="form-group">
            <label>Badge caption</label>
            <input type="text" name="badge_caption" class="form-control" value="{{ old('badge_caption', $template->badge_caption) }}">
        </div>
        <div class="form-group">
            <label>Badge subtext</label>
            <textarea name="badge_subtext" class="form-control" rows="2">{{ old('badge_subtext', $template->badge_subtext) }}</textarea>
        </div>

        <button type="submit" class="admin-btn admin-btn--primary">Save Template</button>
    </form>
</div>
@endsection
