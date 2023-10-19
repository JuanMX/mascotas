{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Create new adopter" size="lg">
    <form method="POST" id="formCreate" >
        @csrf

        {{-- Basic input --}}
        <div class="row">
            <div class="col">
                <x-adminlte-input name="forename" id="forename" label="Forename" placeholder="" label-class=""></x-adminlte-input>
            </div>
            <div class="col">
                <x-adminlte-input name="surname" id="surname" label="Surname" placeholder="" label-class=""></x-adminlte-input>
            </div>
        </div>
       
        <div class="row">
            <div class="col">
                <x-adminlte-input name="phone" id="phone" label="Phone" placeholder="" label-class="" type="tel"></x-adminlte-input>
            </div>
            <div class="col">
                <x-adminlte-input name="email" id="email" label="email" placeholder="" label-class="" type="email"></x-adminlte-input>
            </div>
        </div>

       

        
        <div class="row">
            <div class="col">
                {{-- Input number minimal --}}
                <x-adminlte-input name="age" id="age" label="Age" placeholder="" type="number"
                    min=1 max=999>
                </x-adminlte-input>
            </div>
            <div class="col">
                {{-- Example with placeholder (for Select) --}}
                @php 
                    $adopter_types = Helper::getAdopterType();
                @endphp
                <x-adminlte-select name="adopterType" id="adopterType" label="Type of adopter">
                    <x-adminlte-options :options="$adopter_types" disabled=""
                        placeholder="Select type"/>
                </x-adminlte-select>
            </div>
        </div>

        {{-- Minimal with placeholder --}}
        <x-adminlte-textarea label="Address" name="address" id="address" placeholder=""/>

    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" class="{{Helper::getAdoptionColor()[0]}}" icon="fas fa-lg fa-save" form="formCreate" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
