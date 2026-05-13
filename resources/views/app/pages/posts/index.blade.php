@extends('layouts.application')
@section('page-title')
    {{ 'Anew Avenue Biomagnestim | Blog Posts' }}
@stop
@section('styles')
    @parent
@stop
@section('content')
    @include('partials.header', ['title' => ''])

    <div class="container">
        <h2>
            {{ __('Blog Posts') }} 
            <a href="{{ route('app.posts.new') }}"><button class="editor-new fa fa-plus"></button></a>
        </h2><br/>
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="posts" >
                <thead>
                    <tr>
                        <th class="align-center" style="width: 5%;">{{ __('ID') }}</th>
                        <th class="align-left" style="width: 45%;">{{ __('Title') }}</th>
                        <th class="align-left" style="width: 30%;">{{ __('Slug') }}</th>
                        <th class="align-left" style="width: 5%;">{{ __('Published') }}</th>
                        <th class="align-left" style="width: 15%;">{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            // Delete a record
            $('#posts').on('click', 'button.editor-remove', function (e) {
                e.preventDefault();

                $post_id = $(this).attr('data-id')

                var confirmDialog = confirm("Are you sure you wish to delete this post? / ¿Estás seguro de que deseas eliminar esta publicación?");
                if (confirmDialog == true) {
                    $.ajax({
                        url: '/posts/'+$post_id,
                        type: 'DELETE',
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            } );

            $('#posts').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    search: "Search / Buscar:",
                    searchPlaceholder: "Search... / Buscar...",
                    processing: "Processing... / Procesando...",
                    lengthMenu: "Show _MENU_ entries / Mostrar _MENU_ entradas",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries / Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "No entries found / Sin entradas",
                    infoFiltered: "(filtered from _MAX_ total / filtrado de _MAX_ totales)",
                    zeroRecords: "No matching records found / No se encontraron registros",
                    emptyTable: "No data available / No hay datos disponibles",
                    paginate: {
                        first: "First / Primero",
                        last: "Last / Último",
                        next: "Next / Siguiente",
                        previous: "Previous / Anterior"
                    }
                },
                ajax: { url : '/posts/datatables' },
                columns: [
                    { data: 'id', name: 'id', className: 'dt-body-center' },
                    { data: 'post_title', name: 'post_title' },
                    { data: 'post_slug', name: 'post_slug' },
                    {
                        data: 'isPublished',
                        className: "dt-body-center",
                        orderable: false,
                        render: function ( data, type, row, meta ) {
                            if (data == null) {
                                return 'No';
                            } else {
                                return 'Yes';
                            }
                        }
                    },
                    {
                        data: 'id',
                        className: "dt-body-center",
                        orderable: false,
                        render: function ( data, type, row, meta ) {
                            return '<button class="editor-remove fa fa-trash-o fa-2x" style="color: red;" data-id="'+data+'"></button><a href="/posts/'+data+'/edit"><button class="editor-edit fa fa-edit fa-2x" data-toggle="modal" data-target="#postModal" data-title="Edit Post" data-id="'+data+'"></button></a><a href="/posts/'+data+'"><button class="editor-edit fa fa-eye fa-2x"></button></a>';
                        }
                    }
                ]
            });
        });
    </script>
@stop
