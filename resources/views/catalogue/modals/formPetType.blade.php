{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Create or edit" size="md" disable-animations>
    <form method="POST" id="formCreate" >
        @csrf
        @method('PATCH')
        {{-- With prepend slot --}}
        <x-adminlte-input name="name" id="name" label="Type" placeholder="" label-class=""></x-adminlte-input>
        <x-adminlte-input name="id" id="id" type="hidden" placeholder="" label-class=""></x-adminlte-input>

    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" class="bg-blue" icon="fas fa-lg fa-save" form="formCreate" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
