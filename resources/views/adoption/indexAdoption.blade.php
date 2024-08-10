@extends('adminlte::page')

@section('title', 'Request adoption')

@section('content_header')
    <h2><i class="{{Helper::getAdoptionIcon()[0]}}" aria-hidden="true"></i> Request adoption</h2>
    <h4>Choose one of these availible pets</h4>
@stop

@section('content')
    
    <div class="pt-3 pb-5">
        <table id="table-pet" class="table table-sm table-hover" style="width: 100%;">
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

@stop

@section('js')
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.4.1/js/dataTables.rowGroup.min.js" defer></script>
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
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[0]}} btn-adopt" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Start adoption"><i class="fas fa-hand-holding-heart" aria-hidden="true"></i></button>`;
                    }
                }
            ],
            "rowGroup": {
                dataSrc: ["type"],
                startRender: function(rows, group) {
                    var table = $('#table-pet').DataTable();
                    var dataSrc = table.rowGroup().dataSrc()
                    return rows.cell(rows[0], 1).render('display');
                }
            },
            "order": [
                [1, 'asc'],
            ],
            "columnDefs": [
                {
                    targets: 1,
                    visible: true,
                    render: function(data) {

                        var pet_type = {{Js::from(Helper::getPetType())}};
                        
                        return pet_type[data];
                    }
                }
            ],
            "pageLength": 50
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
            var typeName = {{Js::from(Helper::getPetType())}}[data['type']]+'-'+data['name'];
            const toKebabCase = str =>
            str &&
            str
                .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
                //.map(x => x.toLowerCase())
                .join('-');
            window.location.href = "/adoption/new-adoption-request/"+data['id']+'/'+ toKebabCase(typeName);
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