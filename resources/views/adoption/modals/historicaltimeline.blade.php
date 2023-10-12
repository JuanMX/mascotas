{{-- Custom --}}
<x-adminlte-modal id="modalCustom" title="Historical" size="lg" theme="teal"
    icon="fas fa-tasks" v-centered static-backdrop scrollable>
    <div style="height:800px;">

        <!-- Main node for this component -->
        <div class="timeline">
        <!-- Timeline time label -->
        <div class="time-label">
            <span class="bg-purple">23 Aug. 2019</span>
        </div>
        <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-arrow-alt-circle-down bg-purple"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
            <!-- Time -->
            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
            <!-- Header. Optional -->
            <h3 class="timeline-header">Llegada al refugio de mascotas</h3>
            <!-- Body -->
            <div class="timeline-body">
                Mascota en perfecto estado de salud.
                Esto se toma de la tabla pets.
            </div>
            <!-- Placement of additional controls. Optional -->
            <div class="timeline-footer">
                <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
            </div>
            </div>
        </div>

        <!-- Timeline time label -->
        <div class="time-label">
            <span class="bg-blue">23 Aug. 2019</span>
        </div>
        <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-hand-holding-heart bg-blue"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
            <!-- Time -->
            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
            <!-- Header. Optional -->
            <h3 class="timeline-header">Solicitada la adopción de {Nombre Mascota} por {Nombre Adoptante}</h3>
            <!-- Body -->
            <div class="timeline-body">
                {adoption->note}
            </div>
            <!-- Placement of additional controls. Optional -->
            <div class="timeline-footer">
                <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
            </div>
            </div>
        </div>

        <!-- Timeline time label -->
        <div class="time-label">
            <span class="bg-green">23 Aug. 2019</span>
        </div>
        <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-check-circle bg-green"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
            <!-- Time -->
            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
            <!-- Header. Optional -->
            <h3 class="timeline-header">Aceptada la adopción de {Nombre Mascota} por {Nombre Adoptante}</h3>
            <!-- Body -->
            <div class="timeline-body">
                {adoption->note}
            </div>
            <!-- Placement of additional controls. Optional -->
            <div class="timeline-footer">
                <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
            </div>
            </div>
        </div>

        <!-- Timeline time label -->
        <div class="time-label">
            <span class="bg-red">23 Aug. 2019</span>
        </div>
        <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-times-circle bg-red"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
            <!-- Time -->
            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
            <!-- Header. Optional -->
            <h3 class="timeline-header">Rechazada la adopción de {Nombre Mascota} por {Nombre Adoptante}</h3>
            <!-- Body -->
            <div class="timeline-body">
                {adoption->note}
            </div>
            <!-- Placement of additional controls. Optional -->
            <div class="timeline-footer">
                <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
            </div>
            </div>
        </div>


        <!-- Timeline time label -->
        <div class="time-label">
            <span class="bg-yellow">23 Aug. 2019</span>
        </div>
        <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-heart-broken bg-yellow"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
            <!-- Time -->
            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
            <!-- Header. Optional -->
            <h3 class="timeline-header">Devolución de {Nombre Mascota} por {Nombre Adoptante}</h3>
            <!-- Body -->
            <div class="timeline-body">
                {adoption->note}
            </div>
            <!-- Placement of additional controls. Optional -->
            <div class="timeline-footer">
                <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
            </div>
            </div>
        </div>

        <!-- Timeline time label -->
        <div class="time-label">
            <span class="bg-red">23 Aug. 2019</span>
        </div>
        <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-ban bg-red"></i>
            <!-- Timeline item -->
            <div class="timeline-item">
            <!-- Time -->
            <span class="time"><i class="fas fa-clock"></i> 12:05</span>
            <!-- Header. Optional -->
            <h3 class="timeline-header">Eliminación de {Nombre Mascota}</h3>
            <!-- Body -->
            <div class="timeline-body">
                {adoption->note}
            </div>
            <!-- Placement of additional controls. Optional -->
            <div class="timeline-footer">
                <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
            </div>
            </div>
        </div>
            
        <!-- The last icon means the story is complete -->
        <div>
            <i class="fas fa-flag-checkered"></i>
        </div>
        </div>
    </div>

    <x-slot name="footerSlot">
        
    </x-slot>
</x-adminlte-modal>