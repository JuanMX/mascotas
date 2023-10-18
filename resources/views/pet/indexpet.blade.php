@extends('adminlte::page')

@section('title', 'Manage Pets')

@section('content_header')
    <h2>Manage Pets</h2>
@stop

@section('content')
@section('plugins.Datatables', true)
@section('plugins.Select2', true)
    
    <div class="pb-3">
        <button type="button" class="btn btn-sm btn-primary" id="btn-new-record" name="btn-new-record"><i class="fas fa-plus-circle" aria-hidden="true"></i> New Record</button>
    </div>
    
    </div class="pt-3">
        <table id="table-pet" class="table table-sm table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@include('pet.modals.modalformpet')
@stop

@section('css')
    <link href="{{ secure_asset('css/styles_modal_fullscreen.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatableUsuario = $('#table-pet').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "list-pet",
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
                    "data": "type",
                },
                {
                    "data": "age",
                },
                {
                    "data": "note",
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

            //$('#formularioCrearUsuarioModal')[0].reset();

            //$('#modalMin').modal('handleUpdate');
            $('#modalMin').modal('show');
        });

        $('#form-new-record').on('submit', function(e){

            e.preventDefault();

            formDataCrearUsuarioModal = new FormData($('#form-new-record')[0]);

            $.ajax({
                url: 'crearUsuario',
                type: 'POST',
                dataType: 'json',
                data: formDataCrearUsuarioModal,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                beforeSend: function() {
                    
                    $('#btn-form-save').prop('disabled',true);
                    $('#btn-form-save').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Guardando...');
                }
            })
            .always(function() {
                
                $('#btn-form-save').prop('disabled',false);
                $('#btn-form-save').html('<i class="far fa-save"></i> Guardar');
            })
            .done(function(response) {
                if(response.success) {
                                            
                    $('#tablaUsuario').DataTable().ajax.reload(null, false);
                    $('#modalCrearUsuario').modal('hide');
                    $('#formularioCrearUsuarioModal')[0].reset();                                    
                        
                    
                } else {
                    muestraErrores(response, '');
                }
            })
            .fail(function(response) {
                mensajeOcurrioIncidente();
            });
        });

        $('#table-pet tbody').off('click', 'button.btn-edit');
        $('#table-pet tbody').on('click', 'button.btn-edit', function(event) {
            event.preventDefault();
            $('#modal-fullscreen-xl').modal('handleUpdate');
            $('#modal-fullscreen-xl').modal('show');
            /*
            var currentRow = $(this).closest("tr");
            var data = $('#table-all-adopters').DataTable().row(currentRow).data();

            postFormData = new FormData();
            postFormData.append("_token", $("#_token").val());
            postFormData.append("id", data['id']);
            
            $.ajax({
                url: 'timeline-adopter',
                type: 'POST',
                dataType: 'json',
                data: postFormData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                beforeSend: function() {
                }
            })
            .always(function() {

            })
            .done(function(response) {
                if(response.success) {
                    console.log(response);
                    var adopter_type = @json(Helper::getAdopterType());
                    $('#modalCustom').find('.modal-title').text(response.adopter_data.forename +" "+ response.adopter_data.surname + '\'s Timeline Type ' + adopter_type[response.adopter_data.type]);
                    $('#modalCustom').find('.modal-body').html(response.data);
                    $('#modalCustom').modal('show');
                    
                } else {
                    //muestraErrores(response, '');
                }
            })
            .fail(function() {
                //mensajeOcurrioIncidente();
            });
            */
        });
    });
</script>
@stop