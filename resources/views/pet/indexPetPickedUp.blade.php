@extends('adminlte::page')

@section('title', 'Mark pets picked up')

@section('content_header')
    <h2><i class="{{Helper::getAdoptionIcon()[9]}}" aria-hidden="true"></i> &nbsp; Mark pets picked up</h2> 
@stop

@section('content')
    <x-adminlte-alert theme="warning" title="Use the 'Actions' column ONLY if the pet was picked up" dismissable></x-adminlte-alert>
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
                    <th> Pet Name   </th>
                    <th> Pet Type   </th>
                    <th> Pet Note   </th>
                    <th> Actions    </th>
                    <th> Updated At </th>
                </tr>
            </thead>
            <tbody> </tbody>
        </table>
    </div>

@include('pet.modals.formPetReturn')
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
                url: "/pet/list-pet-adopter-adoption-with-statuses",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : { 
                    'pet_status' : 6,
                    'adoption_status' : 0,
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
                    
                    render: function ( data, type, row ) {
                        
                        var pet_type = {{Js::from(Helper::getAdopterType())}};
                        
                        return pet_type[data];
                    }
                    
                },
                {
                    "data": "age",
                },
                {
                    "data": "status",
                    render: function ( data, type, row ) {
                        
                        var pet_status = {{Js::from(Helper::getAdopterStatus())}};
                        
                        return pet_status[data];
                    }
                },
                {
                    "data": "note",
                },
                {
                    "data": "petname",
                },
                {
                    "data": "pettype",
                    
                    render: function ( data, type, row ) {
                        
                        var pet_type = {{Js::from(Helper::getPetType())}};
                        
                        return pet_type[data];
                    }
                    
                },
                {
                    "data": "petnote",
                },
                {
                    "data": "pet_id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[9]}} btn-action" value="[${row['adopter_id']},${data}]" data-toggle="tooltip" data-placement="bottom" title="Use this button only if the pet was picked up"><i class="{{Helper::getAdoptionIcon()[9]}}" aria-hidden="true"></i></button>`;
                    }
                },
                {
                    "data": "updated_at",
                }
            ],
            "order": [
                [9, 'desc'],
            ],
            "columnDefs": [
                {
                    targets: [9],
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

        $('#formReturn').on('submit', function(e){

            e.preventDefault();

            postFormData = new FormData($('#formReturn')[0]);

            $.ajax({
                url: 'pet-picked-up',
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
                $('#formReturn')[0].reset();                                    

            })
            .fail(function(response) {
                myHelper_swalGenericError();
            });
        });

        $('#table-pet tbody').off('click', 'button.btn-action');
        $('#table-pet tbody').on('click', 'button.btn-action', function(event) {
            
            var me=$(this),
            id=me.attr('value');

            event.preventDefault();

            $('#formReturn')[0].reset();
            $('#modalMin').modal('show');
            $('#id').val(id);
        });
    });
</script>
@stop