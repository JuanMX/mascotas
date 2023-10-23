{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Create new adopter" size="lg">
    <form method="POST" id="formCreate" >
        @csrf

        Adopter info.

        {{-- Minimal with placeholder --}}
        <x-adminlte-textarea label="Optional Note for the requested return" name="note" id="note" placeholder=""/>

    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" class="{{Helper::getAdoptionColor()[3]}}" icon="fas fa-lg fa-save" form="formCreate" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
