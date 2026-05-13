@extends('layouts.application')
@section('page-title')
    {{'Anew Avenue Biomagnestim | Blog posts'}}
@stop
@section('styles')
    @parent
@stop
@section('content')
    @include('partials.header', ['title' => 'New Post'])

    <div class="container">
        <form action="{{ route('app.posts.store') }}" method="POST">
            @csrf

            @include('app.pages.posts.form')
            
            <button type="submit">Save</button>
        </form>
    </div>
@endsection
@section('javascripts')
    @parent

    <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        $( document ).ready(function() {
            var editor_config = {
                path_absolute : "/",
                selector: "textarea",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no"
                    });
                }
            };

            tinymce.init(editor_config);

            $( "#send" ).click(function(ev) {
                ev.preventDefault()

                data = {
                    recipients: $('#recipients').val(),
                    subject: $('#subject').val(),
                    content: tinyMCE.get('content').getContent()
                }

                if (data.recipients == '' || data.recipients == null) {
                    alert('Recipients must be filled out.')
                    $("#recipients").focus();
                } else if (data.subject == '' || data.subject == null) {
                    alert('Subject must be filled out.')
                    $("#subject").focus();
                } else if (data.content == '' || data.content == null) {
                    alert('Content must be filled out.')
                    $("#content").focus();
                } else {
                    $('#form-part').hide();
                    $('#loader-part').show();

                    $.ajax({
                        url: "{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/admin/mail",
                        type: 'POST',
                        data: data,
                        dataType: 'JSON',
                        success: function (data) { 
                            location.reload();
                        }
                    });
                }
            });
        });

    </script>
@stop
