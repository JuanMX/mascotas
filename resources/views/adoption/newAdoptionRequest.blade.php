@extends('adminlte::page')

@section('title', 'New adoption request')

@section('content_header')
@stop

@section('content')
    @if($pet->status==0)
    <div class="container pt-3">
        {{-- With multiple slots, and lg size --}}
        <x-adminlte-input name="iSearch" label="You can search for previous adopters if you want" placeholder="Search an adopter by surname, email or phone" igroup-size="lg">
            <x-slot name="appendSlot">
                <x-adminlte-button theme="outline-danger" label="Go!" id="btn-search-adopter"/>
            </x-slot>
            <x-slot name="prependSlot">
                <div class="input-group-text text-danger">
                    <i class="fas fa-search"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
    <div class="pt-3">
        <x-adminlte-card title=" New adoption request" theme="primary" icon="{{Helper::getAdoptionIcon()[0]}}">
            <form method="POST" id="formCreate" >
                @csrf
                <x-adminlte-input name="petid" id="petid" type="hidden" placeholder="" label-class=""></x-adminlte-input>
                
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

            <x-adminlte-button label="Request Adoption" type="submit" class="{{Helper::getAdoptionColor()[0]}}" icon="{{Helper::getAdoptionIcon()[0]}}" form="formCreate" id="btn-save"/>
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
    <script>
        $(document).ready( function () {
            
        });
    </script>
@stop