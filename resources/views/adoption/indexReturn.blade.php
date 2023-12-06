@extends('adminlte::page')

@section('title', 'Return pet')

@section('content_header')
    <h2><i class="{{Helper::getAdoptionIcon()[3]}}" aria-hidden="true"></i> &nbsp; Apply for pet return</h2>
@stop

@section('content')
@section('plugins.Datatables', true)
    
    <div class="pt-3">
        <table id="table-pet" class="table table-sm table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Adopter Info</th>
                    <th>Name Adopter</th>
                    <th>email</th>
                    <th>Note</th>
                    <th>Pet Name</th>
                    <th>Pet Type</th>
                    <th>Pet Recent Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@include('adoption.modals.formReturn')
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
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.4.1/js/dataTables.rowGroup.min.js" defer></script>
<script src="{{ secure_asset('js/helper_swal.js') }}?v={{ env('VERSION_CSS_JS') }}"></script>
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
                url: "{{Request::root()}}"+"/pet/list-pet-and-its-adopter",
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
                },
                {
                    "data": "email",
                },
                {
                    "data": "note",
                },
                {
                    "data": "petname",
                }, {
                    "data": "pettype",
                    render: function ( data, type, row ) {
                        
                        var type = {{Js::from(Helper::getPetType())}};
                        
                        return type[data];
                    }
                },{
                    "data": "petnote",
                },
                {
                    "data": "adopter_id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[3]}} btn-action" value="[${data}, ${row['pet_id']}]" data-toggle="tooltip" data-placement="bottom" title="Start return/refund"><i class="{{Helper::getAdoptionIcon()[3]}}" aria-hidden="true"></i></button>`;
                    }
                }
            ],
            
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                // Show error in console
                console.error(message);
            }
            return true;
        });

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            var type = {{Js::from(Helper::getAdopterType())}};
            return '<div class="container-fluid"> More Adopter Info <br> Address: '+d.address+'<br> Phone: '+d.phone+'<br> Adopter Type: '+type[d.type]+'<br>Age: '+d.age+'</div>';
        }

        // Add event listener for opening and closing details
        $('#table-pet tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable.row( tr );

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

        $('#table-pet tbody').off('click', 'button.btn-action');
        $('#table-pet tbody').on('click', 'button.btn-action', function(event) {
            event.preventDefault();
            var currentRow = $(this).closest("tr");
            var data = $('#table-pet').DataTable().row(currentRow).data();
            var me=$(this),
            arr_adopterId_petId = me.attr('value');

            (async () => {
                const { value: note } = await Swal.fire({
                    input: "textarea",
                    title: "Please add a note explain your reasons",
                    inputPlaceholder: "Please add a note explain your reasons",
                    inputAttributes: {
                        "aria-label": "Please add a note explain your reasons"
                    },
                    showCancelButton: true
                });
                if (note) {
                    
                    postFormData = new FormData();
                    postFormData.append("_token", $("#_token").val());
                    postFormData.append("note", note);
                    postFormData.append("arr_adopterId_petId", arr_adopterId_petId);
                    $.ajax({
                        url: 'return-request',
                        type: 'POST',
                        dataType: 'json',
                        data: postFormData,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        beforeSend: function() {
                            myHelper_swalWorking();
                        }
                    })
                    .always(function() {
                        Swal.close();
                    })
                    .done(function(response) {
                        Swal.close();
                        Swal.fire({
                            title: "Done",
                            text: "A return request was created for " + data['name'] + " ( " + data['petname'] + " ) ",
                            type: "success"
                        });
                        $('#table-pet').DataTable().ajax.reload(null, false);
                    })
                    .fail(function(response) {
                        Swal.close();
                        myHelper_swalGenericError();
                    });
                }
            })()
        });
    });
</script>
@stop