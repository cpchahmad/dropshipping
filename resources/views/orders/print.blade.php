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

    <script>
        $(document).ready(function () {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        });

    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full py-2">
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
    <div class="content w-100 mb-5">
        <div class="block-content font-size-sm pb-2 bg-white" id="print">
            <div class="d-flex justify-content-between align-middle">
                <h3>Packing Slip</h3>
                <h5>
                    Order {{ $order->name }}
                    <br>
                    {{ $order->date }}
                </h5>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6">
                    <label for="">SHIP TO</label>
                    <span>{{ $order->ship_add }}</span>
                </div>
                <div class="col-md-6">
                    <label for="">BILL TO</label>
                    <span>{{ $order->bill_address }}</span>
                </div>

            </div>

            <hr>
            <div class="d-flex justify-content-between align-middle">
                <h3>Items</h3>
                <h5>Quantity</h5>
            </div>
            <ul class="list-unstyled">
                @foreach($items as $line)
                    @php
                        $item = \App\LineItem::find($line['id']);
                    @endphp
                    <li class='row d-flex align-items-center py-2 border-bottom item-li' action="{{ route('admin.store.order.vendor') }}" method="POST">
                        @csrf
                        <div class="col-2 align-middle d-flex justify-content-between">
                            <img src="{{ $item->img }}" alt='No img' class="img-fluid" style="width: 100px; height: auto;">
                        </div>
                        <div class='col-7'>
                            <span class="d-block font-weight-lighter">{{$item->title}}     @if(!(is_null($item->sku)) && $item->sku != '')<span class=" font-weight-lighter"><span class="font-weight-bold"> [SKU: </span> {{$item->sku}}]</span>@endif</span>
                            @if(isset($item->shopify_variant->title) && $item->shopify_variant->title !== "Default Title")<span class="d-block font-weight-bold">{{$item->shopify_variant->title}}</span>@endif
                        </div>
                        <div class="text-right col-3">
                            <span class="">
                               {{$line['temp_qty']}} of {{ $item->quantity }}
                            </span>
                        </div>
                    <li/>
                @endforeach
            </ul>

            <div class="row text-center">
                <div class="col-md-12">
                    <h5>Thanks for Shipping with us!</h5>
                    <p>Contact us if you have any questions or concerns regarding the items</p>
                    @if($order->fulfillment_status == 'partial')
                        <p class="text-danger">This is not your full order and some items might be pending or will come in next shipment. Please contact us for more info</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->

@endsection
