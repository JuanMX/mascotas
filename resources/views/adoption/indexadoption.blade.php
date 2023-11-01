@extends('adminlte::page')

@section('title', 'Apply for adoption')

@section('content_header')
    <h2><i class="{{Helper::getAdoptionIcon()[0]}}" aria-hidden="true"></i> Apply for adoption</h2>
    <h4>Choose one of these availible pets</h4>
@stop

@section('content')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
    
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

@include('adoption.modals.formAdopter')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatable = $('#table-pet').DataTable({
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
                data : { 'status' : 0 },
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
                    "data": "type",//myTODO use the helper for pet type
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
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[0]}} btn-adopt" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Start adoption"><i class="fas fa-hand-holding-heart" aria-hidden="true"></i></button>`;
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

        $('#table-pet tbody').off('click', 'button.btn-adopt');
        $('#table-pet tbody').on('click', 'button.btn-adopt', function(event) {

            event.preventDefault();
            var currentRow = $(this).closest("tr");
            var data = $('#table-pet').DataTable().row(currentRow).data();

            //myTODO remove or use the Helper:: in pet data[type] 
            $('#formCreate')[0].reset();
            $('#modalMin').find('.modal-title').text("Adopter for " + data['name'] + " Type " + data['type']);
            $('#petid').val(data['id']);
            $('#modalMin').modal('show');

        });

        $('#formCreate').on('submit', function(e){

            e.preventDefault();

            postFormData = new FormData($('#formCreate')[0]);

            $.ajax({
                url: 'adoption-request',
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
                $('#btn-save').html('<i class="{{Helper::getAdoptionIcon()[0]}}"></i> Request Adoption');
            })
            .done(function(response) {
                if(response.success) {
                                            
                    $('#table-pet').DataTable().ajax.reload(null, false);
                    $('#modalMin').modal('hide');
                    $('#formCreate')[0].reset();                                    
                        
                    
                } else {
                    swal.fire({
                        title: 'Internal error',
                        text: "Something went wrong",
                        type: 'error',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                    });
                }
            })
            .fail(function(response) {
                swal.fire({
                    title: 'Internal error',
                    text: "Something went wrong",
                    type: 'error',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                });
            });
        });
    });
</script>
@stop