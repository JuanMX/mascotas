@extends('adminlte::page')

@section('title', 'Adopter Type')

@section('content_header')
    <h2>Adopter Type</h2>
@stop

@section('content')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
    
    <div class="pb-3">
        <button type="button" class="btn btn-sm btn-primary" id="btn-new-record" name="btn-new-record"><i class="fas fa-plus-circle" aria-hidden="true"></i> New Record</button>
    </div>
    </div>
        <table id="table" class="table table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    @include('catalogue.modals.formSimple')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatable = $('#table').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "list-adopter-type",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json'
            },
            fail: function (data) {
                console.log(data);
            },
            done: function (data)
            {
                console.log(data);
            },
            "columns": [
                {
                    "data": "name",
                },
                {
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm btn-primary btn-edit" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;
                            <button type="button" class="btn btn-sm btn-danger btn-delete" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>`;
                    }
                }
            ]
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                // Se imprime este error en consola, para no mostrar al usuario
                console.error(message);
            }
            return true;
        });

        $('#btn-new-record').click(function(event) {

            event.preventDefault();

            $('#formCreate')[0].reset();
            $("[name='_method']").val("POST")
            $('#modalMin').modal('show');

        });

        $('#table tbody').off('click', 'button.btn-edit');
        $('#table tbody').on('click', 'button.btn-edit', function(event) {
            event.preventDefault();

            $('#formCreate')[0].reset();
            $("[name='_method']").val("PATCH")
            $('#modalMin').modal('show');
            
            var currentRow = $(this).closest("tr");
            var data = $('#table').DataTable().row(currentRow).data();

            $('#name').val(data['name']);
            $('#id').val(data['id']);
        });

        $('#formCreate').on('submit', function(e){
            e.preventDefault();
            
            postFormData = new FormData();
            postFormData.append("_token", "{{ csrf_token() }}");
            postFormData.append("_method", $("[name='_method']").val());
            postFormData.append("id", $('#id').val());
            postFormData.append("name", $('#name').val());

            var ajaxURL = $("[name='_method']").val() === "PATCH" ? 'edit-adoptertype' : 'create-adoptertype';
            
            $.ajax({
                url: ajaxURL,
                type: 'POST',
                dataType: 'json',
                data: postFormData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                beforeSend: function() {
                    $('#btn-save').prop('disabled',true);
                    $('#btn-save').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Please wait...');
                }
            })
            .always(function() {
                $('#btn-save').prop('disabled',false);
                $('#btn-save').html('<i class="far fa-save"></i> Save');
            })
            .done(function(response) {
                if(response.success) {
                    $('#table').DataTable().ajax.reload(null, false);
                    $('#modalMin').modal('hide');
                    $('#formCreate')[0].reset();
                } else {
                    //muestraErrores(response, '');
                }
            })
            .fail(function() {
                //mensajeOcurrioIncidente();
            });
        });

        $('#table tbody').off('click', 'button.btn-delete');
        $('#table tbody').on('click', 'button.btn-delete', function(event) {
            
            var me=$(this),
            id=me.attr('value');
            Swal.fire({
                title: '¿Continue?',
                text: "It's a delete action",
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Confirm',
                confirmButtonColor: '#28A745',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                reverseButtons: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            url: 'delete-adoptertype',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                _token:  "{{ csrf_token() }}",
                                _method: "DELETE",
                                id: id
                            }
                        }).done(function(data) {
                            resolve(data);
                        }).fail(function() {
                            //mensajeOcurrioIncidente();
                        });
                    });
                }
            }).then(function(data) {
                if (data.value.success) {
                    $('#table').DataTable().ajax.reload(null, false);
                } else {
                    //muestraErrores(data.value, '');
                }
            });
        });
    });
</script>
@stop