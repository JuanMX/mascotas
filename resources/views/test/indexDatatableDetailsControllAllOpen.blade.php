@extends('adminlte::page')

@section('title', 'Test')

@section('content_header')
    <h2><i class="fas fa-tools" aria-hidden="true"></i> Test Datatable details control all opened as default</h2>
@stop

@section('content')
    <div>
        <table id="table" class="table table-hover">
            <thead>
                <tr>
                    <th>Note</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>Type</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    
@stop

@section('css')
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
        myDatatable = $('#table').DataTable({
            "autoWidth": false,
            "processing": true,
            "responsive": false,
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
                //data : { 'status' : 0 },
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
                },{
                    "data": "email",
                },{
                    "data": "type",
                    render: function ( data, type, row ) {
                        
                        var type = {{Js::from(Helper::getAdopterType())}};
                        
                        return type[data];
                    }
                }, {
                    "data": "age",
                },{
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm btn-primary btn-edit" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Edit but this button doesn't work"><i class="fa fa-edit" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Delete but this button doesn't work"><i class="fa fa-trash" aria-hidden="true"></i></button>`;
                    }
                }
            ],
            "order": [
                [1, 'asc'],
            ]
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                console.error(message);
            }
            return true;
        }).on('draw', function () {
            $("#table > tbody > tr").each(function () {
                var currentRow = $(this);

                var row = myDatatable.row( currentRow );

                if(row.length > 0){
                    if ( !row.child.isShown() ) {
                        // Open this row
                        row.child( format(row.data()) ).show();
                        currentRow.addClass('shown');
                    }
                }
            });
        });

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<div>'+d.note+'</div>';
        }

        // Add event listener for opening and closing details
        $('#table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = myDatatable.row( tr );

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