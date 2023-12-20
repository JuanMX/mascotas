@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1> '{{ config('app.name') }}' Dashboard </h1>
@stop

@section('content')

<div class="container-fluid">

    {{-- TOTAL Pets Summary  --}}
    <div class="row">
        <div class="col-sm">
            <x-adminlte-info-box title="Pets" text="" icon="fas fa-fw fa-paw text-light" id="widgetTotalPets"
            theme="success" description="TOTAL All Pets" progress=0 progress-theme="teal"/>
        </div>

        <div class="col-sm">
            
            <x-adminlte-info-box title="Adopted Pets" text="" icon="{{Helper::getAdoptionIcon()[0]}} text-light" class="{{Helper::getAdoptionColor()[0]}}"
            id="widgetAdoptedPets" progress=0 progress-theme="teal"
            description=""/>
        </div>

        <div class="col-sm">
            <x-adminlte-info-box title="Not Adopted Pets" text="" icon="{{Helper::getAdoptionIcon()[3]}} text-light"
            id="widgetNotAdoptedPets" class="bg-yellow" progress=0 progress-theme="teal"
            description=""/>
        </div>

        <div class="col-sm">
            <x-adminlte-info-box title="Removed Pets" text="" icon="{{Helper::getAdoptionIcon()[6]}} text-light"
            id="widgetDeletedPets" class="bg-red" progress=0 progress-theme="teal"
            description=""/>
        </div>
    </div>

    {{-- Pets Summary  --}}
    <div class="row">
        <div class="col-sm">
            <x-adminlte-small-box id="widgetCurrentNotAdoptedPets" title="" text="Current Not Adopted Pets" icon="fas fa-fw fa-paw" class="bg-yellow" url="pet/pet" url-text="Go to Manage Pets"/>
        </div>

        <div class="col-sm">
            <x-adminlte-small-box id="widgetPetsPendingPickedUp" title="" text="Pets pending to be picked up" icon="{{Helper::getAdoptionIcon()[9]}}" class="{{Helper::getAdoptionColor()[9]}}" url="pet/pickedup" url-text="Go to Mark Picked Up Pets"/>
        </div>

        <div class="col-sm">
            <x-adminlte-small-box id="widgetPetsPendingReturn" title="" text="Pets pending to return at the shelter" icon="{{Helper::getIconArrivalShelter()}}" class="{{Helper::getColorArrivalShelter()}}" url="pet/return" url-text="Go to Mark Pets Returning to the shelter"/>
        </div>
    </div>

    {{-- Requests Summary  --}}

    <div class="row">
        <div class="col-sm">
            <x-adminlte-small-box id="widgetPetAdoptionRequests" title="" text="Adoption Requests" icon="{{Helper::getAdoptionIcon()[0]}}" class="{{Helper::getAdoptionColor()[0]}}" url="adoption/deliberate-adoption" url-text="Go to Deliberate Adoption Requests"/>
        </div>

        <div class="col-sm">
            <x-adminlte-small-box id="widgetPetReturnRequests" title="" text="Return Requests" icon="{{Helper::getAdoptionIcon()[3]}}" class="{{Helper::getAdoptionColor()[3]}}" url="adoption/deliberate-return" url-text="Go to Deliberate Requests Requests"/>
        </div>
    </div>

    {{-- Chart Current Year Arrivals and Adoptions  --}}

    <div class="row">
        <div class="col-sm">

            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                            </button>
                        </div>
                </div>
                <div class="card-body">
                    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="chartArrivalsAdoptions" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%; display: block; width: 572px;" width="715" height="312" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>

            </div>



            
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-sm">
            <div class="card card-secondary">
                <div class="card-header border-transparent">
                    <h3 class="card-title">10 Latest adoptions actions</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm m-0">
                            <thead>
                                <tr>
                                    <th>Adopter</th>
                                    <th>Pet</th>
                                    <th>Adoption type</th>
                                    <th>Note</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="badge badge-success">Shipped</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                    <td>Samsung Smart TV</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                    <td>iPhone 6 Plus</td>
                                    <td><span class="badge badge-danger">Delivered</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                    <td>Samsung Smart TV</td>
                                    <td><span class="badge badge-info">Processing</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                    <td>Samsung Smart TV</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                    <td>iPhone 6 Plus</td>
                                    <td><span class="badge badge-danger">Delivered</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="badge badge-success">Shipped</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="badge badge-success">Shipped</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="badge badge-success">Shipped</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="badge badge-success">Shipped</span></td>
                                    <td>
                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="card-footer clearfix">
                    <a href="javascript:void(0)" class="btn btn-sm btn-block btn-info"><i class="fas fa-sync-alt"></i> Refresh this table</a>
                    
                </div>

            </div>
        </div>
    </div>

    
    
        
    
</div>
@stop

@section('css')
@stop

@section('js')
@stop

@push('js')
<script src="{{ secure_asset('js/helper_swal.js') }}?v={{ env('VERSION_CSS_JS') }}"></script>
<script>
    $(document).ready(function() {

        $(document).Toasts('create', {
            title: 'Welcome',
            autohide: true,
            delay: 5000,
            class: 'bg-green',
            icon: 'far fa-check-circle',
        })

        let widgetTotalPetsJs      = new _AdminLTE_InfoBox('widgetTotalPets');
        let widgetAdoptedPetsJs    = new _AdminLTE_InfoBox('widgetAdoptedPets');
        let widgetNotAdoptedPetsJs = new _AdminLTE_InfoBox('widgetNotAdoptedPets');
        let widgetDeletedPetsJs    = new _AdminLTE_InfoBox('widgetDeletedPets');

        let widgetCurrentNotAdoptedPetsJS = new _AdminLTE_SmallBox('widgetCurrentNotAdoptedPets');
        let widgetPetsPendingPickedUpJS   = new _AdminLTE_SmallBox('widgetPetsPendingPickedUp');
        let widgetPetsPendingReturnJS     = new _AdminLTE_SmallBox('widgetPetsPendingReturn');

        let widgetPetAdoptionRequestsJS   = new _AdminLTE_SmallBox('widgetPetAdoptionRequests');
        let widgetPetReturnRequestsJS     = new _AdminLTE_SmallBox('widgetPetReturnRequests');

        let text;
        let progress;
        let description;
        let data;
        let rep;
        let repError="¿ ?";

        postFormData = new FormData();
        postFormData.append('_token', "{{csrf_token()}}");

        $.ajax({
            url: 'dashboard-total',
            type: 'POST',
            data: postFormData,
            dataType: 'json',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
        })
        .done(function(response) {
            if(response.success){

                text = response.data.total;
                data = {text};
                widgetTotalPetsJs.update(data);

                text = response.data.adopted + ' / ' + response.data.total;
                progress = Math.round(response.data.adopted  * 100 / response.data.total);
                description = progress + '% of total adopted pets';
                data = {text, description, progress};
                widgetAdoptedPetsJs.update(data);

                text = response.data.not_adopted + ' / ' + response.data.total;
                progress = Math.round(response.data.not_adopted  * 100 / response.data.total);
                description = progress + '% of total not adopted pets';
                data = {text, description, progress};
                widgetNotAdoptedPetsJs.update(data);

                text = response.data.deleted + ' / ' + response.data.total;
                progress = Math.round(response.data.deleted  * 100 / response.data.total);
                description = progress + '% of total removed pets';
                data = {text, description, progress};
                widgetDeletedPetsJs.update(data);
                
            }
            else{
                myHelper_toastErrorWithMessage(response.error);
            }
        })
        .fail(function(response) {
            myHelper_toastErrorWithMessage(response.responseJSON.error);
        });

        $.ajax({
            url: 'dashboard-pets-pending',
            type: 'POST',
            data: postFormData,
            dataType: 'json',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            beforeSend: function() {
                widgetCurrentNotAdoptedPetsJS.toggleLoading();
                widgetPetsPendingPickedUpJS.toggleLoading();
                widgetPetsPendingReturnJS.toggleLoading();
            }
        })
        .always(function() {
            widgetCurrentNotAdoptedPetsJS.toggleLoading();
            widgetPetsPendingPickedUpJS.toggleLoading();
            widgetPetsPendingReturnJS.toggleLoading();
        })
        .done(function(response) {
            if(response.success){

                rep = (response.data.current_not_adopted).toString();
                data = {title: rep};
                widgetCurrentNotAdoptedPetsJS.update(data);

                rep = (response.data.pending_picked_up).toString();
                data = {title: rep};
                widgetPetsPendingPickedUpJS.update(data);

                rep = (response.data.pending_return).toString();
                data = {title: rep};
                widgetPetsPendingReturnJS.update(data);
            }
            else{
                myHelper_toastErrorWithMessage(response.error);
            }
        })
        .fail(function(response) {
            data = {title: repError};
            widgetCurrentNotAdoptedPetsJS.update(data);
            widgetPetsPendingPickedUpJS.update(data);
            widgetPetsPendingReturnJS.update(data);
            myHelper_toastErrorWithMessage(response.responseJSON.error);
        });

        $.ajax({
            url: 'dashboard-pets-requests',
            type: 'POST',
            data: postFormData,
            dataType: 'json',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            beforeSend: function() {
                widgetPetAdoptionRequestsJS.toggleLoading();
                widgetPetReturnRequestsJS.toggleLoading();
            }
        })
        .always(function() {
            widgetPetAdoptionRequestsJS.toggleLoading();
            widgetPetReturnRequestsJS.toggleLoading();
        })
        .done(function(response) {
            if(response.success){

                rep = (response.data.pets_requested_adoption).toString();
                data = {title: rep};
                widgetPetAdoptionRequestsJS.update(data);

                rep = (response.data.pets_requested_return).toString();
                data = {title: rep};
                widgetPetReturnRequestsJS.update(data);
            }
            else{
                myHelper_toastErrorWithMessage(response.error);
            }
        })
        .fail(function(response) {
            data = {title: repError};
            widgetPetAdoptionRequestsJS.update(data);
            widgetPetReturnRequestsJS.update(data);
            myHelper_toastErrorWithMessage(response.responseJSON.error);
        });

        var areaChartData = {
            labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                label               : 'Digital Goods',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [28, 48, 40, 19, 86, 27, 90, 80, 17, 1, 22, 11]
                },
                {
                label               : 'Electronics',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : [65, 59, 80, 81, 56, 55, 40, 48, 65, 42, 31, 1]
                },
            ]
        }

        var areaChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                gridLines : {
                    display : false,
                }
                }],
                yAxes: [{
                gridLines : {
                    display : false,
                }
                }]
            }
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#chartArrivalsAdoptions').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })
    });

</script>
@endpush