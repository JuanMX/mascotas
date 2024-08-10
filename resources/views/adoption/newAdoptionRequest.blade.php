@extends('adminlte::page')

@section('title', 'New adoption request')

@section('content_header')
@stop

@section('content')
    @if($pet->status==0)
    <div class="container pt-3">
        {{-- With multiple slots, and lg size --}}
        <x-adminlte-input type="search" name="inputSearch" id="inputSearch" label="You can search for previous adopters if you want" placeholder="Search an adopter by surname, email or phone" igroup-size="lg">
            <x-slot name="appendSlot">
                <x-adminlte-button theme="outline-danger" label="Go!" id="btn-search-adopter" type="submit"  title="Search and fill the form"/>
            </x-slot>
            <x-slot name="prependSlot">
                <div class="input-group-text text-danger">
                    <i class="fas fa-search"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>

    <div class="container table-responsive pt-3" id='tableForManySearchResults' width="100%"></div>

    <div class="pt-3">
        <x-adminlte-card title=" New adoption request" theme="primary" icon="{{Helper::getAdoptionIcon()[0]}}">
            <form method="POST" id="formCreate" >
                @csrf
                <x-adminlte-input name="petid" id="petid" type="hidden" placeholder="" label-class="" value="{{$pet->id}}"></x-adminlte-input>
                <x-adminlte-input name="adopterid" id="adopterid" type="hidden" placeholder="" label-class="" value=""></x-adminlte-input>
                
                {{-- Basic input --}}
                <div class="row">
                    <div class="col">
                        <x-adminlte-input name="forename" id="forename" label="Forename" placeholder="" label-class="" required=""></x-adminlte-input>
                    </div>
                    <div class="col">
                        <x-adminlte-input name="surname" id="surname" label="Surname" placeholder="" label-class="" required=""></x-adminlte-input>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col">
                        <x-adminlte-input name="phone" id="phone" label="Phone" placeholder="" label-class="" type="tel"></x-adminlte-input>
                    </div>
                    <div class="col">
                        <x-adminlte-input name="email" id="email" label="email" placeholder="" label-class="" type="email" required=""></x-adminlte-input>
                    </div>
                </div>

            

                
                <div class="row">
                    <div class="col">
                        {{-- Input number minimal --}}
                        <x-adminlte-input name="age" id="age" label="Age" placeholder="" type="number" required=""
                            min=1 max=150>
                        </x-adminlte-input>
                    </div>
                    <div class="col">
                        {{-- Example with placeholder (for Select) --}}
                        @php 
                            $adopter_types = Helper::getAdopterType();
                        @endphp
                        <x-adminlte-select name="type" id="type" label="Type of adopter" required="">
                            <x-adminlte-options :options="$adopter_types" disabled=""
                                placeholder="Select type"/>
                        </x-adminlte-select>
                    </div>
                </div>

                {{-- Minimal with placeholder --}}
                <x-adminlte-textarea label="Address" rows=1  name="address" id="address" placeholder="" required=""/>

                <x-adminlte-textarea label="Adoption note" name="note" id="note" placeholder="Adopter. Why do you want to adopt the pet?" required=""/>

            </form>

            <x-adminlte-button label="Request Adoption" type="submit" class="{{Helper::getAdoptionColor()[0]}} btn-block" icon="{{Helper::getAdoptionIcon()[0]}}" form="formCreate" id="btn-save"/>
        </x-adminlte-card>
    </div>
    @endif

    <div class="pt-3 pb-5">
        <x-adminlte-card title="Pet: {{$pet->name}} | Type: {{Helper::getPetType()[$pet->type]}}" theme="{{$pet->status==0 ? 'purple' : 'danger'}}" icon="fas fa-info-circle" >
            
            @if($pet->status!=0) <span class="bg-yellow">The pet isn't available for adoption.</span><br><br>@endif
            
            <div class="table-responsive">
                <table class="table table-sm m-0" id="table-info-pet">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Pet Note</th>
                            <th>Updated at</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{$pet->name}}</td>
                            <td>{{Helper::getPetType()[$pet->type]}}</td>
                            <td>{{$pet->age}}</td>
                            <td><span class="badge {{$pet->status==0 ? 'bg-purple' : 'bg-red'}}">{{Helper::getPetStatus()[$pet->status]}}</span></td>
                            <td>{{$pet->note}}</td>
                            <td>{{$pet->updated_at}} | {{$pet->updated_at->diffForHumans()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-adminlte-card>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ secure_asset('js/helper_swal.js') }}?v={{ env('VERSION_CSS_JS') }}"></script>
    <script>
        var many_adopters;
        function fillAdopterForm( index=0 ){
            console.log(many_adopters);
            
            $("#forename").val(many_adopters[index]['forename']);
            $("#surname").val(many_adopters[index]['surname']);
            $("#phone").val(many_adopters[index]['phone']);
            $("#email").val(many_adopters[index]['email']);
            $("#age").val(many_adopters[index]['age']);
            $("#type").val(many_adopters[index]['type']);
            $("#address").val(many_adopters[index]['address']);
            $("#adopterid").val(many_adopters[index]['id']);
            

            console.log("is filled?");
            console.log($("#forename").val());
            myHelper_toastInfoWithMessage("Done. Please fill the 'Adoption note'");
            
        }
        $(document).ready( function () {
            
            console.log(document.getElementById("formCreate").elements);
            $('#btn-search-adopter').on('click', function(e){
                e.preventDefault();

                postFormData = new FormData();

                postFormData.append('_token', "{{csrf_token()}}");
                postFormData.append('inputSearch', $("#inputSearch").val());

                $.ajax({
                    url: '/adopter/adopter-search-field',
                    type: 'POST',
                    dataType: 'json',
                    data: postFormData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    beforeSend: function() {
                        $('#tableForManySearchResults').empty();
                        myHelper_swalWorking();
                    }
                })
                .always(function() {
                    Swal.close();
                })
                .done(function(response) {
                    if(response.success) {         
                        many_adopters = response.data;
                        console.log(response.data);
                        if(response.data_count > 1){
                            table_many_results = "<table class='table table-sm m-0 table-hover' width='100%' id='table-matches'>";
                            table_many_results += "<caption class='text-danger'>"+"'"+$("#inputSearch").val()+"' matches with more than one result. Please select one adopter in the list and use de 'Actions' button to fill the form"+"</caption>";
                            table_many_results += "<thead><tr><th>Name</th><th>Age</th><th>Type</th><th>Phone</th><th>email</th><th>Actions</th><tr/></thead>";
                            table_many_results += "<tbody>";
                            for(index = 0; index < response.data_count; index++){
                                
                                row_result = "<tr>" + "<td>" + response.data[index]['forename'] + " " + response.data[index]['surname'] +"</td>" + "<td>" + response.data[index]['age'] + "</td>"+ "<td>" + {{Js::from(Helper::getAdopterType())}}[ response.data[index]['type']] +"</td>" + "<td>" + response.data[index]['phone'] + "</td>" + "<td>" + response.data[index]['email'] + "</td>" + "<td>" + `<button onclick='fillAdopterForm(${index})' type="button" class="btn btn-sm bg-blue btn-fill-form" value="${index}" data-toggle="tooltip" data-placement="bottom" title="Fill the form with this data"><i class="fas fa-clipboard-check" aria-hidden="true"></i></button>`+ "</td>" + "</tr>";
                                table_many_results += row_result;
                            }
                            table_many_results += "</tbody></table>";

                            $('#tableForManySearchResults').append(table_many_results);

                            myHelper_toastInfoWithMessage("'"+$("#inputSearch").val()+"' matches with more than one result");
                            
                        }
                        else{
                            fillAdopterForm();
                        }
                    } else {
                        Swal.close();
                        myHelper_toastErrorWithMessage(response.message);
                    }
                })
                .fail(function(response) {
                    Swal.close();
                    myHelper_swalGenericError();
                });
            });

            $('#formCreate').on('submit', function(e){

                e.preventDefault();

                postFormData = new FormData($('#formCreate')[0]);

                $.ajax({
                    url: '/adoption/adoption-request',
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
                    $('#btn-save').html('<i class="{{Helper::getAdoptionIcon()[0]}}"></i> Request Adoption');
                })
                .done(function(response) {
                    /*
                    if(response.success) {
                                                
                        $('#table-pet').DataTable().ajax.reload(null, false);
                        $('#modalMin').modal('hide');
                        $('#formCreate')[0].reset();                                    
                            
                        
                    } else {
                        swal.fire({
                            title: 'Internal error',
                            text: "Something went wrong",
                            type: 'error',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                        });
                    }
                    */
                })
                .fail(function(response) {
                    swal.fire({
                        title: 'Internal error',
                        text: "Something went wrong",
                        type: 'error',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                    });
                });
            });

            
        });
    </script>
@stop