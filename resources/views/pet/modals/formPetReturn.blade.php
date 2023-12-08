{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Add a note" size="lg">
    <form method="POST" id="formReturn" >

        @csrf

        {{-- Minimal with placeholder --}}
        <x-adminlte-textarea label="Note" name="note" id="note" placeholder=""/>
        

        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="">
            <label for="customCheckbox1" class="custom-control-label">Append this note to the Pet Note</label>
        </div>
    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" class="{{Helper::getColorArrivalShelter()}}" icon="fas fa-lg fa-save" form="formReturn" id="btn-save"/>
        <x-adminlte-button label="Close" theme="secondary" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
