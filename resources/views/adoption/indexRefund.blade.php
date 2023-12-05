@extends('adminlte::page')

@section('title', 'Return pet')

@section('content_header')
    <h2>Apply for pet return</h2>
    <h4>Choose one of these availible pets</h4>
@stop

@section('content')
@section('plugins.Datatables', true)
    
    </div class="pt-3">
        <table id="table-pet" class="table table-sm table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@include('adoption.modals.formReturn')
@stop

@section('css')
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
                url: "{{Request::root()}}"+"/pet/list-pets-with-status",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : { 'status' : 2},
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
                    "data": "status",
                    render: function ( data, type, row ) {
                        
                        var pet_status = {{Js::from(Helper::getPetStatus())}};
                        
                        return pet_status[data];
                    }
                },
                {
                    "data": "note",
                },
                {
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[3]}} btn-action" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Start return/refund"><i class="{{Helper::getAdoptionIcon()[3]}}" aria-hidden="true"></i></button>`;
                    }
                }
            ]
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                // Show error in console
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

        $('#table-pet tbody').off('click', 'button.btn-action');
        $('#table-pet tbody').on('click', 'button.btn-action', function(event) {
            event.preventDefault();
            var currentRow = $(this).closest("tr");
            var data = $('#table-pet').DataTable().row(currentRow).data();

            $('#modalMin').find('.modal-title').text("Return for " + data['name'] + " Type " + data['type']);

            postFormData = new FormData();
            postFormData.append("_token", $("#_token").val());
            postFormData.append("id", data['id']);
            
            $.ajax({
                url: 'adopter-info-for-pet',
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

                console.log(response);
                $('#modalMin').find('.modal-body').html(response);
                $('#modalMin').modal('show');
                    

            })
            .fail(function() {
                //mensajeOcurrioIncidente();
            });
        });
    });
</script>
@stop