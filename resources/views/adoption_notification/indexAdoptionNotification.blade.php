@extends('adminlte::page')

@section('title', 'Adoption Status Notification')

@section('content_header')
    <h2><i class="far fa-bell" aria-hidden="true"></i> Adoption Status Notification</h2>
@stop

@section('content')

    <div class="table-responsive">
        <table class="table table-sm m-0 table-hover" id="table-notifications" width="100%">
            <thead>
                <tr>
                    <th>Adopter</th>
                    <th>Adopter's phone</th>
                    <th>Adopter's email</th>
                    <th>Adopter's type</th>
                    <th>Adopter's age</th>
                    <th>Pet</th>
                    <th>Pet's type</th>
                    <th>Pet's age</th>
                    <th>Pet's note</th>
                    <th>Adoption status</th>
                    <th>Adoption Note</th>
                    <th>Adoption Date</th>
                    <th>N</th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>
    </div>
@stop

@section('css')
@stop

@section('js')
<script src="https://cdn.datatables.net/plug-ins/1.10.16/api/row().show().js"></script>
<script type="text/javascript">
    $(document).ready( function () {
        
        $.fn.dataTable.ext.errMode = 'none';
        dataTable = $('#table-notifications').DataTable({
            "language": {
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "/adoption-notification",
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
                    "data": "adopters_name",
                },
                {
                    "data": "adopters_phone",
                },
                {
                    "data": "adopters_email",
                },
                {
                    "data": "adopters_type",
                    
                    render: function ( data, type, row ) {
                        
                        var render_type = {{Js::from(Helper::getAdopterType())}};
                        
                        return render_type[data];
                    }
                    
                },
                {
                    "data": "adopters_age",
                },
                {
                    "data": "pets_name",
                },
                {
                    "data": "pets_type",
                    render: function ( data, type, row ) {
                        
                        var render_type = {{Js::from(Helper::getPetType())}};
                        
                        return render_type[data];
                    }
                },
                {
                    "data": "pets_age",
                },
                {
                    "data": "pets_note",
                },
                {
                    "data": "adoptions_status",
                    render: function ( data, type, row ) {
                        
                        var render_type = {{Js::from(Helper::getAdoptionStatus())}};
                        
                        return render_type[data];
                    }
                },
                {
                    "data": "adoptions_note",
                },
                {
                    "data": "adoptions_date",
                },
                {
                    "data": "adoptions_id",
                },
            ],
            
            "order": [
                [11, 'desc'],
            ],
            "columnDefs": [
                {
                    targets: [12],
                    visible: false
                }
            ],
            "initComplete": function(settings, json) {
                
                if({{$id}} > 0){
                    regExSearch = '^' + {{$id}} +'$';

                    dataTable
                    .column(12)
                    .search(regExSearch, true, false)
                    .draw()
                }
            }
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                // Show error in console
                console.error(message);
            }
            return true;
        });

        
    });
</script>
@stop