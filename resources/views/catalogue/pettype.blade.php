@extends('adminlte::page')

@section('title', 'Tipos de mascota')

@section('content_header')
    <h1>Tipos de mascota</h1>
@stop

@section('content')
@section('plugins.Datatables', true)
    
    </div>
        <table id="tablaUsuario" class="table table-hover">
            <input type="hidden" name="_token" content="{{ csrf_token() }}" value="{{ csrf_token() }}" id="_token">
            <thead>
                <tr>
                    <th>Nick</th>
                    <th>Tipo de usuario</th>
                    <th>Género</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script type="text/javascript">
    $(document).ready( function () {
        //$.fn.dataTable.ext.errMode = 'none';
        datatableUsuario = $('#tablaUsuario').DataTable({
            "language": {
                //"url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },  
            "searching": true,       
            "ajax": {
                type: "POST",
                url: "listarUsuarios",
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
                    "data": "nick",
                }, {
                    "data": "rol",
                    render: function ( data, type, row ) {

                        if (data=='ROL_ADMINISTRADOR'){
                            return '<span class="badge badge-primary">Administrador<span>';
                        }
                        if (data=='ROL_BASICO'){
                            return '<span class="badge badge-secondary">Básico<span>';
                        }
                    }
                }, {
                    "data": "genero",
                    render: function ( data, type, row ) {

                        if (data=='MASCULINO'){
                            return 'Masculino';
                        }
                        else if (data=='FEMENINO'){
                            return 'Femenino';
                        }
                        else {
                            return 'Otro';
                        }
                    }
                }, {
                    "data": "id",
                    "orderable": false,
                    render: function ( data, type, row ) {
                        if(row.bloqueado){
                            return `<button type="button" class="btn btn-sm btn-primary btn-editar" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;
                                <button type="button" class="btn btn-sm btn-warning btn-desbloquear" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Desbloquear"><i class="fa fa-unlock" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm btn-danger btn-eliminar" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Eliminar "><i class="fa fa-trash" aria-hidden="true"></i></button>`;
                        }
                        else{
                            return `<button type="button" class="btn btn-sm btn-primary btn-editar" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;
                                <button type="button" class="btn btn-sm btn-warning btn-bloquear" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Bloquear"><i class="fa fa-lock" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm btn-danger btn-eliminar" value="${data}" data-toggle="tooltip" data-placement="bottom" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>`;
                        }
                        
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
    });
</script>
@stop