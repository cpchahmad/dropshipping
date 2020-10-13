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
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                <h1 class="flex-sm-fill h3 my-2">
                    Customers
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('admin.customers') }}">Customers</a>
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
                <h3 class="block-title">Customers</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                @if(count($customers)>0)
                    <table class="table table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Orders count</th>
                            <th>Total Spent</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td class="font-w600">
                                <a href="/admin/orders?customer={{ $customer['id'] }}">
                                    {{ $customer['first_name']}} {{ $customer['last_name'] }}
                                </a>
                            </td>
                            <td class="font-w600">
                                {{ $customer['orders_count'] }}
                            </td>
                            <td class="font-w600">
                                ${{ $customer['total_spent'] }}
                            </td>
                            <td class="font-w600 text-right">
                                <a href="/admin/orders?customer={{ $customer['id'] }}" class="btn btn-primary btn-sm">View Orders</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No data!</p>
                @endif
                <div class="d-flex justify-content-end">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->
@endsection
