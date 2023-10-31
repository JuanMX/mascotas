{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Create or edit" size="lg">
    <form method="POST" id="formCreate" >
        @method('PATCH')
        @csrf
        <x-adminlte-input name="id" id="id" type="hidden" placeholder="" label-class=""></x-adminlte-input>

        {{-- With prepend slot --}}
        <x-adminlte-input name="name" id="name" label="Pet's name" placeholder="" label-class="">
            
        </x-adminlte-input>

        {{-- Input number minimal --}}
        <x-adminlte-input name="age" id="age" label="Age" placeholder="" type="number"
             min=1 max=999 step=".01">
        </x-adminlte-input>

        {{-- Example with placeholder (for Select) --}}
        
        @php 
            $pet_statuses = Helper::getPetStatus();
            $pet_types = Helper::getPetType();
        @endphp

        <x-adminlte-select name="status" id="status" label="Pet status">
            <x-adminlte-options :options="$pet_statuses" disabled=""
                placeholder="Select status"/>
        </x-adminlte-select>

        <x-adminlte-select name="type" id="type" label="Type of pet">
            <x-adminlte-options :options="$pet_types" disabled=""
                placeholder="Select type"/>
        </x-adminlte-select>

        {{-- Minimal with placeholder --}}
        <x-adminlte-textarea label="Description" name="note" id="note" placeholder=""/>

    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" class="{{Helper::getColorArrivalShelter()}}" icon="fas fa-lg fa-save" form="formCreate" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
