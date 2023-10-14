@extends('adminlte::page')

@section('title', 'Timeline')

@section('content_header')
    <h2>Timeline</h2>
@stop

@section('content')
@section('plugins.Datatables', true)
    
    <h2>Pets Table</h2>
    <div>
        <table id="table-all-pets" class="table table-hover">
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

    <h2 class="pt-5">Adopters Table</h2>
    <div>
        <table id="table-all-adopters" class="table table-hover">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@include('adoption.modals.timeline')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatableAllPets = $('#table-all-pets').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
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
                        return `<button type="button" class="btn btn-sm btn-primary btn-timeline" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Watch Timeline"><i class="far fa-eye" aria-hidden="true"></i></button>`;
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
                    //muestraErrores(response, '');
                }
            })
            .fail(function() {
                //mensajeOcurrioIncidente();
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
                        return `<button type="button" class="btn btn-sm btn-primary btn-timeline-adopter" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Watch Timeline"><i class="far fa-eye" aria-hidden="true"></i></button>`;
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
                    //muestraErrores(response, '');
                }
            })
            .fail(function() {
                //mensajeOcurrioIncidente();
            });

        });

    });
</script>
@stop