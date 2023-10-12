@extends('adminlte::page')

@section('title', 'Historical')

@section('content_header')
    <h1>Historical</h1>
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
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@include('adoption.modals.historicaltimeline')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatableUsuario = $('#table-all-pets').DataTable({
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
                        return `<button type="button" class="btn btn-sm btn-primary btn-timeline" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Watch Historical"><i class="far fa-eye" aria-hidden="true"></i></button>`;
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
            url: 'historical-pet',
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
                $('#modalCustom').find('.modal-title').text(response.pet_arrival.name + '\'s Historical');
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