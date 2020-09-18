@extends('layouts.backend')

@section('css_before')
<link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('js_after')
<!-- Page JS Plugins -->
<script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>

<!-- Page JS Helpers (Slick Slider Plugin) -->
<script>jQuery(function(){ One.helpers('slick'); });</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#smart_collection').select2();
        $('#custom_collection').select2();

    });
</script>
@endsection

@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Order Details
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('admin.orders') }}">Orders</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ route('admin.orders.details', $order->id) }}">{{ $order->name }}</a>
                    </li>
                </ol>
            </nav>
        </div>
   </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="">
        <div class="row justify-content-center">

            <div class="col-md-8">
                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">
                            Fulfillment
                            <span class="{{ $order->status }}">{{ $order->fulfillment_status ? $order->fulfillment_status : 'Pending' }}</span>
                        </h1>
                    </div>
                    <div class="block-content ">
                        @if($order->line_items_count >0)
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ $order->line_item_details }}

                            </tbody>
                        </table>
                        @else
                            <p>No line items</p>
                        @endif
                    </div>
                </div>

                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">
                            Payment
                            <span class="{{ $order->payment_status }}">{{ $order->financial_status }}</span>
                        </h1>
                    </div>
                    <div class="block-content ">
                        {{ $order->payment_details }}
                    </div>
                </div>
            </div>


            <div class="col-md-4">

                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">Customer</h1>
                    </div>
                    <div class="block-content">
                        <p class="font-size-sm text-muted" >
                            {{ $order->customer ? $order->customer_name : 'No customer' }}
                        </p>
                    </div>
                </div>

                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">Shipping Address</h1>
                    </div>
                    <div class="block-content">
                        <p class="font-size-sm text-muted" >
                            {{ $order->ship_address }}
                        </p>
                    </div>
                </div>


                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">Billing Address</h1>
                    </div>
                    <div class="block-content">
                        <p class="font-size-sm text-muted" >
                            {{ $order->bill_address }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- END Page Content -->




@endsection

