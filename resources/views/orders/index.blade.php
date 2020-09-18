@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
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

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                <h1 class="flex-sm-fill h3 my-2">
                    Orders
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('admin.orders') }}">Orders</a>
                        </li>
                    </ol>
                </nav>
            </div>
       </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-header">
                <h3 class="block-title">Orders</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                @if(count($orders)> 0)
                    <table class="table table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">#</th>
                            <th>Date</th>
                            <th>Total line Item Price</th>
                            <th>Currency</th>
                            <th>Payment Status</th>
                            <th>Fulfillment Status</th>
                            <th>Items</th>
                            <th>Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.details', $order->id) }}">{{ $order->name }}</a>
                            </td>
                            <td class="font-w600">
                                {{ $order->date }}
                            </td>
                            <td class="font-w600">
                                ${{ $order->price }}
                            </td>
                            <td class="font-w600">
                                {{ $order->currency }}
                            </td>
                            <td class="font-w600">
                                <span class="{{ $order->payment_status }}">{{ $order->financial_status }}</span>
                            </td>
                            <td class="font-w600">
                                <span class="{{ $order->status }}">{{ $order->fulfillment_status ? $order->fulfillment_status : 'Pending' }}</span>
                            </td>
                            <td class="font-w600">
                                {{ $order->line_items_count }}
                            </td>
                            <td class="font-w600">
                                {{ $order->customer ? $order->customer_name : '-'}}
                            </td>
                            <td class="font-w600">
                                <a class="btn btn-sm btn-alt-primary js-tooltip-enabled float-right" href="{{ route('admin.orders.details', $order->id) }}" data-toggle="tooltip" title="" data-original-title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No Data!</p>
                @endif
                <div class="d-flex justify-content-end">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->
@endsection
