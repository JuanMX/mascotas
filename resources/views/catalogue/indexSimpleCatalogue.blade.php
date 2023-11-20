@extends('adminlte::page')

@section('title', 'Catalogues')

@section('content_header')
    <h2>{{ ucwords( strtolower( str_replace('_',' ',$catalogue) ) ) }}</h2>
@stop

@section('content')
    <x-adminlte-alert id="adminlte-alert" theme="warning" title="Changes made will affect all records" dismissable></x-adminlte-alert>

    <div class="pb-3">
        <button type="button" class="btn btn-md btn-primary" id="btn-new-record" name="btn-new-record"><i class="fas fa-plus-circle" aria-hidden="true"></i> New Record</button>
    </div>

    <div class="pb-5">
        <table id="table" class="table table-hover" style="width: 100%;">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Actions</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @include('catalogue.modals.formSimple')
@stop

@section('css')
@stop

@section('js')
<script src="{{ secure_asset('js/helper_swal.js') }}?v={{ env('VERSION_CSS_JS') }}"></script>
<script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.errMode = 'none';
        datatable = $('#table').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "read",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : { 'catalogue' : "{{$catalogue}}" },
                dataType: 'json',
                dataSrc: function (data) {
                    if (!data.success){
                        myHelper_swalGenericError();
                        datatable.destroy();
                        $('#btn-new-record').hide();
                        $('#adminlte-alert').hide();
                        data.data = []; //since datatables will be checking for the object as array
                        return data.data;
                    }
                    else{
                        return data.data;
                    }
                } 
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
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        return `<button type="button" class="btn btn-sm btn-primary btn-edit" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;
                            <button type="button" class="btn btn-sm btn-danger btn-delete" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>`;
                    }
                },
                {
                    "data": "updated_at",
                }
            ],
            "order": [
                [2, 'desc'],
            ],
            "columnDefs": [
                {
                    targets: [2],
                    visible: false
                }
            ]
        }).on('error.dt', function(e, settings, techNote, message) {
            
            if (typeof techNote === 'undefined') {

            } else {
                // Show error in console
                console.error(message);
                myHelper_swalGenericError();
                datatable.destroy();
                $('#btn-new-record').hide();
                $('#adminlte-alert').hide();
                
            }
            return true;
        });

        $('#btn-new-record').click(function(event) {

            event.preventDefault();

            $('#formCreate')[0].reset();
            $("[name='_method']").val("POST")
            $('#modalMin').modal('show');

        });

        $('#table tbody').off('click', 'button.btn-edit');
        $('#table tbody').on('click', 'button.btn-edit', function(event) {
            event.preventDefault();

            $('#formCreate')[0].reset();
            $("[name='_method']").val("PATCH")
            $('#modalMin').modal('show');
            
            var currentRow = $(this).closest("tr");
            var data = $('#table').DataTable().row(currentRow).data();

            $('#name').val(data['name']);
            $('#id').val(data['id']);
        });

        $('#formCreate').on('submit', function(e){
            e.preventDefault();
            
            postFormData = new FormData();
            postFormData.append("_token", "{{ csrf_token() }}");
            postFormData.append("_method", $("[name='_method']").val());
            postFormData.append("id", $('#id').val());
            postFormData.append("name", $('#name').val());
            postFormData.append("catalogue", "{{$catalogue}}");
            
            var ajaxURL = $("[name='_method']").val() === "POST" ? 'create' : 'update';
            
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
                if(response.success) {
                    $('#table').DataTable().ajax.reload(null, false);
                    $('#modalMin').modal('hide');
                    $('#formCreate')[0].reset();
                } else {
                    myHelper_swalGenericError();
                }
            })
            .fail(function() {
                myHelper_swalGenericError();
            });
        });
        
        $('#table tbody').off('click', 'button.btn-delete');
        $('#table tbody').on('click', 'button.btn-delete', function(event) {
            
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
                                id: id,
                                catalogue: "{{$catalogue}}"
                            }
                        }).done(function(data) {
                            resolve(data);
                        }).fail(function() {
                            myHelper_swalGenericError();
                        });
                    });
                }
            }).then(function(data) {
                if (data.value.success) {
                    $('#table').DataTable().ajax.reload(null, false);
                } else {
                    myHelper_swalGenericError();
                }
            });
        });
    });
</script>
@stop