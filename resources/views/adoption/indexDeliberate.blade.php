@extends('adminlte::page')

@section('title', 'Deliberate requests')

@section('content_header')
    <h2>Deliberate for adopt and return pets</h2>
@stop

@section('content')
@section('plugins.Datatables', true)
    
    <h2><i class="{{Helper::getAdoptionIcon()[0]}}" aria-hidden="true"></i> &nbsp; Adoption</h2>
    <div>
        <table id="table-deliberate-adopt" class="table table-sm table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Note</th>
                    <th>Name Adopter</th>
                    <th>Address</th>
                    <th>email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Pet Name</th>
                    <th>Pet Type</th>
                    <th>Pet Recent Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <h2 class="pt-5"><i class="{{Helper::getAdoptionIcon()[3]}}" aria-hidden="true"></i> &nbsp; Return</h2>
    <div>
        <table id="table-deliberate-return" class="table table-sm table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Note</th>
                    <th>Name Adopter</th>
                    <th>Address</th>
                    <th>email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Pet Name</th>
                    <th>Pet Type</th>
                    <th>Pet Recent Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        td.details-control {
            background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
    </style>
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatableAdopt = $('#table-deliberate-adopt').DataTable({
            "autoWidth": false,
            "processing": true,
            "responsive": false,
            //"serverSide": true,
            "language": {
               // "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "list-adopt-requests",
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
                    "width": "1%",
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": '',
                },
                {
                    "data": "name",
                }, {
                    "data": "address",
                },{
                    "data": "email",
                }, {
                    "data": "phone",
                },{
                    "data": "type",
                }, {
                    "data": "age",
                },{
                    "data": "petname",
                }, {
                    "data": "pettype",
                },{
                    "data": "petnote",
                },{
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[1]}} btn-accept" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Accept"><i class="{{Helper::getAdoptionIcon()[1]}}" aria-hidden="true"></i></button>&nbsp;
                            <button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[2]}} btn-reject" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Reject"><i class="{{Helper::getAdoptionIcon()[2]}}" aria-hidden="true"></i></button>`;
                    }
                }
            ],
            
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                // Se imprime este error en consola, para no mostrar al usuario
                console.error(message);
            }
            return true;
        });
        datatableAdopt.on( 'draw', function () {
            $("#table-deliberate-adopt  > tbody > tr").each(function () {
                var thisrow = $(this);

                var row = datatableAdopt.row( thisrow );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    //row.child.hide();
                    //tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    thisrow.addClass('shown');
                }
            });
        })

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<div>'+d.note+'</div>';
        }

        // Add event listener for opening and closing details
        $('#table-deliberate-adopt tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatableAdopt.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        
    } );
</script>
@stop