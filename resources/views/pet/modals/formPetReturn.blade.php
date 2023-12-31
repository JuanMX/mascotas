{{-- Minimal --}}
<x-adminlte-modal id="modalMin" title="Add a note" size="lg">
    <form method="POST" id="formReturn" >
        @csrf
        
        <x-adminlte-input name="arr_idAdopter_idPet" id="id" type="hidden" placeholder="" label-class=""></x-adminlte-input>

        {{-- Minimal with placeholder --}}
        <x-adminlte-textarea label="Note" name="note" id="note" placeholder="" required/>
        

        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="customCheckbox1" name="append" value="1">
            <label for="customCheckbox1" class="custom-control-label">Append this note to the Pet Note</label>
        </div>
    </form>

    <x-slot name="footerSlot">
        <x-adminlte-button label="Save" type="submit" class="btn" theme="dark" icon="fas fa-lg fa-save" form="formReturn" id="btn-save"/>
        <x-adminlte-button label="Close" theme="default" icon="far fa-times-circle" data-dismiss="modal"/>
    </x-slot>

</x-adminlte-modal>
            


            
          
