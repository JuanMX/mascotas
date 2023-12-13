{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Create new adopter" size="lg">
    <form method="POST" id="formCreate" >
        @csrf
        <x-adminlte-input name="petid" id="petid" type="hidden" placeholder="" label-class=""></x-adminlte-input>
        <x-adminlte-alert theme="info" title="The system tries to prevent duplicates" dismissable>
            If the forename, surname, phone and adopter type matches an existing adopter. The pet will be assigned to that adopter. <br>
            And the remainig data (address, email . . .) updates the adopter info.
        </x-adminlte-alert>
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
                    min=1 max=999>
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

        <x-adminlte-textarea label="Optional adoption note" name="note" id="note" placeholder="Adopter. Why do you want to adopt the pet?" required=""/>

    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Request Adoption" type="submit" class="{{Helper::getAdoptionColor()[0]}}" icon="{{Helper::getAdoptionIcon()[0]}}" form="formCreate" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
