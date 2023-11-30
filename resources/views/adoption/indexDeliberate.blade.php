@extends('adminlte::page')

@section('title', 'Deliberate requests')

@section('content_header')
    <h2><i class="fas fa-balance-scale" aria-hidden="true"></i> &nbsp; Deliberate for adopt and return pets</h2>
@stop

@section('content')
    <x-adminlte-alert theme="info" title="Any action will send an email" dismissable></x-adminlte-alert>
    <h2><i class="{{Helper::getAdoptionIcon()[0]}}" aria-hidden="true"></i> &nbsp; Adoption</h2>
    <div>
        <table id="table-deliberate-adopt" class="table table-bordered table-hover">
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
                },{
                    "data": "adopter_id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[1]}} btn-accept" value="[${data}, ${row['pet_id']}, true]" data-toggle="tooltip" data-placement="bottom" title="Accept"><i class="{{Helper::getAdoptionIcon()[1]}}" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-sm {{Helper::getAdoptionColor()[2]}} btn-reject" value="[${data}, ${row['pet_id']}, false]" data-toggle="tooltip" data-placement="bottom" title="Reject"><i class="{{Helper::getAdoptionIcon()[2]}}" aria-hidden="true"></i></button>&nbsp;
                            `;
                            //<button type="button" class="btn btn-sm bg-blue btn-email" value="[${data}, ${row['pet_id']}, 2]" data-toggle="tooltip" data-placement="bottom" title="Send mail asking information...work in progress" disabled aria-disabled="true"><i class="fas fa-envelope" aria-hidden="true"></i></button>

                    }
                }
            ],
            "rowGroup": {
                dataSrc: ["name", "email"]
            },
            "columnDefs": [
                {
                    targets: [1,2],
                    visible: false
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

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            var type = {{Js::from(Helper::getAdopterType())}};
            return '<div> More Adopter Info <br> Address: '+d.address+'<br> Phone: '+d.phone+'<br> Adopter Type: '+type[d.type]+'<br>Age: '+d.age+'</div>';
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

        $('#table-deliberate-adopt tbody').off('click', 'button.btn-accept,button.btn-reject');
        $('#table-deliberate-adopt tbody').on('click', 'button.btn-accept,button.btn-reject', function(event) {
            event.preventDefault();
            var currentRow = $(this).closest("tr");
            var data = $('#table-deliberate-adopt').DataTable().row(currentRow).data();

            var me=$(this),
            arr_idAdopter_idPet_accepted = me.attr('value');
            (async () => {
                const { value: note } = await Swal.fire({
                    input: "textarea",
                    title: "Email message to: "+data['name']+"<br>"+data['email'],
                    inputPlaceholder: "Any action will send an email ",
                    inputAttributes: {
                        "aria-label": "Any action will send an email "
                    },
                    showCancelButton: true
                });
                if (note) {
                    
                    postFormData = new FormData();
                    postFormData.append("_token", $("#_token").val());
                    postFormData.append("note", note);
                    postFormData.append("arr_idAdopter_idPet_accepted", arr_idAdopter_idPet_accepted);
                    $.ajax({
                        url: 'adoption-deliberated',
                        type: 'POST',
                        dataType: 'json',
                        data: postFormData,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        beforeSend: function() {
                            Swal.fire({
                                title: '<span class="spinner-grow spinner-grow" role="status" aria-hidden="true"></span>&nbsp;Sending ...',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            });
                        }
                    })
                    .always(function() {
                        Swal.close();
                    })
                    .done(function(response) {
                        Swal.close();
                        Swal.fire("Done");
                        $('#table-deliberate-adopt').DataTable().ajax.reload(null, false);
                    })
                    .fail(function(response) {
                        Swal.close();
                        myHelper_swalGenericError();
                    });
                }
            })()
            
        });

        

        datatableReturn = $('#table-deliberate-return').DataTable({
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
                url: "list-return-requests",
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
                    render: function ( data, type, row ) {
                        
                        var type = {{Js::from(Helper::getAdopterType())}};
                        
                        return type[data];
                    }
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
        }).on( 'draw', function () {
            $("#table-deliberate-return  > tbody > tr").each(function () {
                var thisrow = $(this);
                
                var row = datatableReturn.row( thisrow );

                if(row.length > 0){
                    if ( !row.child.isShown() ) {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    thisrow.addClass('shown');
                    }
                }
            });
        });

        // Add event listener for opening and closing details
        $('#table-deliberate-return tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = datatableReturn.row( tr );

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