@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
@section('plugins.Datatables', true)
    <p>Welcome to this beautiful admin panel.</p>

    {{-- Setup data for datatables --}}
@php
$heads = [
    'ID',
    'Name',
    ['label' => 'Phone', 'width' => 40],
    ['label' => 'Actions', 'no-export' => true, 'width' => 5],
];

$btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
$btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
$btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';

$config = [
    'data' => [
        [22, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
        [19, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
        [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],
    ],
    'order' => [[1, 'asc']],
    'columns' => [null, null, null, ['orderable' => false]],
];
@endphp

{{-- Minimal example / fill data using the component slot --}}
<x-adminlte-datatable id="table1" :heads="$heads">
    @foreach($config['data'] as $row)
        <tr>
            @foreach($row as $cell)
                <td>{!! $cell !!}</td>
            @endforeach
        </tr>
    @endforeach
</x-adminlte-datatable>

<h2>Adopter</h2>
<!-- Main node for this component -->
<div class="timeline">
  

  <!-- Timeline time label -->
  <div class="time-label">
    <span class="bg-info">23 Aug. 2019</span>
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
    <span class="bg-info">23 Aug. 2019</span>
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
    <span class="bg-info">23 Aug. 2019</span>
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
    <span class="bg-info">23 Aug. 2019</span>
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
    <span class="bg-info">23 Aug. 2019</span>
  </div>
  <div>
  <!-- Before each timeline item corresponds to one icon on the left scale -->
      <i class="fas fa-ban bg-red"></i>
    <!-- Timeline item -->
    <div class="timeline-item">
    <!-- Time -->
      <span class="time"><i class="fas fa-clock"></i> 12:05</span>
      <!-- Header. Optional -->
      <h3 class="timeline-header">Baneo de {Nombre Adoptante}</h3>
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



<div class="pt-1">
</div>


{{-- Example button to open modal --}}
<x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalCustom" class="bg-teal"/>

@stop

@section('footer')
<div class="pt-5">
A
</div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop