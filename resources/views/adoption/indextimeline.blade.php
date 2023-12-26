@extends('adminlte::page')

@section('title', 'Timeline')

@section('content_header')
    <h2> <i class="fas fa-tasks" aria-hidden="true"></i> &nbsp; Timeline</h2>
@stop

@section('content')
    <h2> <i class="fas fa-fw fa-paw" aria-hidden="true"></i> &nbsp; Pets Table</h2>
    <div>
        <table id="table-all-pets" class="table table-hover display nowrap" style="width: 100%;">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Note</th>
                    <th data-priority="1">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <h2 class="pt-3"> <i class="fas fa-user-alt"></i> &nbsp; Adopters Table</h2>
    <div class="pb-3">
        <table id="table-all-adopters" class="table table-hover display nowrap" style="width:100%">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Forename</th>
                    <th>Surname</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>email</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th data-priority="1">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@include('adoption.modals.timeline')
@stop

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('js')
<script src="{{ secure_asset('js/helper_swal.js') }}?v={{ env('VERSION_CSS_JS') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" defer></script>
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatableAllPets = $('#table-all-pets').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "responsive": true,
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "list-all-pets",
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
                    render: function ( data, type, row ) {
                        
                        var arr_type = @json(Helper::getPetType());
                        return arr_type[data];
                    }
                },
                {
                    "data": "age",
                },
                {
                    "data": "note",
                    render: function ( data, type, row ) {
                        return '<span style="white-space:normal">' + data + "</span>";
                    }
                },
                {
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm bg-teal btn-timeline" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Watch Timeline"><i class="far fa-eye" aria-hidden="true"></i></button>`;
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
    

        $('#table-all-pets tbody').off('click', 'button.btn-timeline');
        $('#table-all-pets tbody').on('click', 'button.btn-timeline', function(event) {
            event.preventDefault();

            var currentRow = $(this).closest("tr");
            var data = $('#table-all-pets').DataTable().row(currentRow).data();

            postFormData = new FormData();
            postFormData.append("_token", $("#_token").val());
            postFormData.append("id", data['id']);
            
            $.ajax({
                url: 'timeline-pet',
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
                    $('#modalCustom').find('.modal-title').text(response.pet_arrival.name + '\'s Timeline');
                    $('#modalCustom').find('.modal-body').html(response.data);
                    $('#modalCustom').modal('show');
                    
                } else {
                    myHelper_swalGenericError();
                }
            })
            .fail(function() {
                myHelper_swalGenericError();
            });

        });

        ///////////////////////////
       //        Adopters       //
      //         Section       //
     ///////////////////////////

        datatableAllAdopters = $('#table-all-adopters').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "responsive": true,
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "list-all-adopters",
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
                    "data": "forename",
                },
                {
                    "data": "surname",
                },
                {
                    "data": "address",
                },
                {
                    "data": "phone",
                },
                {
                    "data": "email",
                },
                {
                    "data": "age",
                },
                {
                    "data": "status",
                    render: function ( data, type, row ) {
                        
                        var adopter_status = @json(Helper::getAdopterStatus());
                        //console.log(adopter_status); //print same array 4 times per row o_O
                        return adopter_status[data];
                    }
                },
                {
                    "data": "type",
                    render: function ( data, type, row ) {
                        
                        var adopter_type = @json(Helper::getAdopterType());
                        //console.log(adopter_status); //print same array 4 times per row o_O
                        return adopter_type[data];
                    }
                },
                {
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm bg-teal btn-timeline-adopter" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Watch Timeline"><i class="far fa-eye" aria-hidden="true"></i></button>`;
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

        $('#table-all-adopters tbody').off('click', 'button.btn-timeline-adopter');
        $('#table-all-adopters tbody').on('click', 'button.btn-timeline-adopter', function(event) {
            event.preventDefault();

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
                    myHelper_swalGenericError();
                }
            })
            .fail(function() {
                myHelper_swalGenericError();
            });

        });

    });
</script>
@stop