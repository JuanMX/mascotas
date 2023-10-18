{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Create or edit" size="lg">
    <form method="POST" id="formCreate" >
        @csrf

        {{-- With prepend slot --}}
        <x-adminlte-input name="petname" id="petname" label="Pet's name" placeholder="" label-class="">
            
        </x-adminlte-input>

        {{-- Input number minimal --}}
        <x-adminlte-input name="iNum" label="Age" placeholder="" type="number"
             min=1 max=999 step=".01">
        </x-adminlte-input>

        {{-- Example with placeholder (for Select) --}}
        
        @php 
            $pet_statuses = Helper::getPetStatus();
            $pet_types = Helper::getPetType();
        @endphp

        <x-adminlte-select name="petStatus" id="petStatus" label="Pet status">
            <x-adminlte-options :options="$pet_statuses" disabled=""
                placeholder="Select status"/>
        </x-adminlte-select>

        <x-adminlte-select name="petType" id="petType" label="Type of pet">
            <x-adminlte-options :options="$pet_types" disabled=""
                placeholder="Select type"/>
        </x-adminlte-select>

        {{-- Minimal with placeholder --}}
        <x-adminlte-textarea label="Description" name="petNote" id="petNote" placeholder=""/>

    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" theme="primary" icon="fas fa-lg fa-save" form="formCreate" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

    {{--<button type="button" class="btn btn-secondary btn-sm mt-0" data-bs-dismiss="modal"><i class="far fa-times-circle"></i>&nbsp;Cerrar</button>--}}
    
    {{--<button type="submit" class="btn btn-primary btn-sm mt-0" form="formularioCrearUsuarioModal" id="btn_guardarCreacion"><i class="far fa-save"></i> Guardar </button>--}}

</x-adminlte-modal>
            


            
          
