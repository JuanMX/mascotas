@extends('adminlte::page')

@section('title', 'Manage Pets')

@section('content_header')
    <h2><i class="fas fa-fw fa-paw" aria-hidden="true"></i> &nbsp; Manage Pets </h2> 
@stop

@section('content')
    <x-adminlte-alert theme="info" title="You can only work on not adopted pets" dismissable></x-adminlte-alert>
    
    <div class="pb-3">
        <button type="button" class="btn btn-md {{Helper::getColorArrivalShelter()}}" id="btn-new-record" name="btn-new-record"><i class="fas fa-plus-circle" aria-hidden="true"></i> New Record</button>
    </div>
    
    <div class="pt-3 pb-3">
        <table id="table-pet" class="table table-sm table-hover" style="width: 100%;">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th> Name       </th>
                    <th> Type       </th>
                    <th> Age        </th>
                    <th> Status     </th>
                    <th> Note       </th>
                    <th> Actions    </th>
                    <th> Updated At </th>
                </tr>
            </thead>
            <tbody> </tbody>
        </table>
    </div>

@include('pet.modals.formPet')
@stop

@section('css')

@stop

@section('js')
<script src="{{ secure_asset('js/helper_swal.js') }}?v={{ env('VERSION_CSS_JS') }}"></script>
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        dataTable = $('#table-pet').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "/pet/list-pets-with-status",
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
                    
                    render: function ( data, type, row ) {
                        
                        var pet_type = {{Js::from(Helper::getPetType())}};
                        
                        return pet_type[data];
                    }
                    
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
                        if(row['status']===0){
                            return `<button type="button" class="btn btn-sm {{Helper::getColorArrivalShelter()}} btn-edit" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;
                            <button type="button" class="btn btn-sm btn-danger btn-delete" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>`;

                        }
                        else{
                            return `<span class="badge bg-info text-dark">Can only edit NOT ADOPTED pets</span>`
                        }
                    }
                },
                {
                    "data": "updated_at",
                }
            ],
            "order": [
                [6, 'desc'],
            ],
            "columnDefs": [
                {
                    targets: [6],
                    visible: false
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

            $('#formCreate')[0].reset();
            $("[name='_method']").val("POST")
            $('#modalMin').modal('show');
        });

        $('#formCreate').on('submit', function(e){

            e.preventDefault();

            postFormData = new FormData($('#formCreate')[0]);

            var ajaxURL = $("[name='_method']").val() === "PATCH" ? 'edit' : 'create';

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
      
                $('#table-pet').DataTable().ajax.reload(null, false);
                $('#modalMin').modal('hide');
                $('#formCreate')[0].reset();                                    

            })
            .fail(function(response) {
                swalGenericError();
            });
        });

        $('#table-pet tbody').off('click', 'button.btn-edit');
        $('#table-pet tbody').on('click', 'button.btn-edit', function(event) {
            event.preventDefault();

            $('#formCreate')[0].reset();
            $("[name='_method']").val("PATCH")
            $('#modalMin').modal('show');

            var currentRow = $(this).closest("tr");
            var data = $('#table-pet').DataTable().row(currentRow).data();

            $('#name').val(data['name']);
            $('#type').val(data['type']);
            $('#age').val(data['age']);
            $('#status').val(data['status']);
            $('#note').val(data['note']);
            $('#id').val(data['id']);
        });

        $('#table-pet tbody').off('click', 'button.btn-delete');
        $('#table-pet tbody').on('click', 'button.btn-delete', function(event) {
            
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
                            url: 'delete',
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
                            swalGenericError();
                        });
                    });
                }
            }).then(function(data) {
                if (data.value.success) {
                    $('#table-pet').DataTable().ajax.reload(null, false);
                } else {
                    swalGenericError();
                }
            });
        });
    });
</script>
@stop