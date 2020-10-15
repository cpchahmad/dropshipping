@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />
@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>]
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script>
        $(function() {

            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            if($('body').find('#canvas-graph-one').length > 0){
                console.log('ok');
                var config = {
                    type: 'bar',
                    data: {
                        labels: JSON.parse($('#canvas-graph-one').attr('data-labels')),
                        datasets: [{
                            label: 'Order Count',
                            backgroundColor: '#00e2ff',
                            borderColor: '#00e2ff',
                            data: JSON.parse($('#canvas-graph-one').attr('data-values')),
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Summary Orders Count'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Value'
                                }
                            }]
                        }
                    }
                };

                var ctx = document.getElementById('canvas-graph-one').getContext('2d');
                window.myBar = new Chart(ctx, config);
            }

            if($('body').find('#canvas-graph-two').length > 0){
                console.log('ok');
                var config = {
                    type: 'line',
                    data: {
                        labels: JSON.parse($('#canvas-graph-two').attr('data-labels')),
                        datasets: [{
                            label: 'Orders Sales',
                            backgroundColor: '#5c80d1',
                            borderColor: '#5c80d1',
                            data: JSON.parse($('#canvas-graph-two').attr('data-values')),
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Summary Orders Sales'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Sales'
                                }
                            }]
                        }
                    }
                };

                var ctx_2 = document.getElementById('canvas-graph-two').getContext('2d');
                window.myLine = new Chart(ctx_2, config);
            }

        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                <h1 class="flex-sm-fill h3 my-2">
                    Sale Reports
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('admin.products.reports') }}">Sale Reports</a>
                        </li>
                    </ol>
                </nav>
            </div>
       </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <form class="d-flex justify-content-end mb-3" method="GET" action="{{ route('admin.products.reports') }}">
            <input type="search" name="datefilter" value="" class="" placeholder="Select date.."/>
            <button class="btn btn-primary ml-2">Apply</button>
        </form>
        <div class="row">
            <div class="col-md-4">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x" >
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Total Sales</div>
                        <div class="font-size-h2 font-w400 text-dark">${{ number_format($orders_sum, 2) }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Total Cost</div>
                        <div class="font-size-h2 font-w400 text-dark">${{ number_format($cost, 2) }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Expenses</div>
                        <div class="font-size-h2 font-w400 text-dark">${{ number_format($expenses_sum, 2) }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Shipping Cost</div>
                        <div class="font-size-h2 font-w400 text-dark">${{ number_format($shipping_sum, 2) }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Profit</div>
                        <div class="font-size-h2 font-w400 text-dark">${{ number_format(($orders_sum - $cost - $expenses_sum - $shipping_sum), 2) }}</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-one" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-two" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- END Page Content -->
@endsection
