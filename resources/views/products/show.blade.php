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
                Product Details
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('admin.products') }}">Products</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ route('admin.products.details', $product->id) }}">{{ $product->title }}</a>
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
            @csrf
            <div class="col-md-6">
                <!-- Slider with dots -->
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Images</h3>
                    </div>
                    @if($product->image_count > 0)
                        <div class="row gutters-tiny items-push js-gallery push pb-3 pl-3">
                            @foreach($product->imgs as $image)
                                <div class="col-6 animated fadeIn">
                                    <div class="options-container fx-item-rotate-r">
                                        <img class="img-fluid options-item" src="{{ $image->src }}" alt="">
                                        <div class="options-overlay bg-black-75">
                                            <div class="options-overlay-content">

                                                <a class="btn btn-sm btn-primary img-lightbox" href="{{ $image->src }}">
                                                    <i class="fa fa-search-plus mr-1"></i> View
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <!-- END Slider with dots -->
                </div>
                <!-- END Dots -->
            </div>

            <div class="col-md-6">
                <!-- Info -->
                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">TITLE </h1>{{ $product->title }}
                    </div>
                    <div class="block-header">
                        <h3 class="block-title" >TYPE </h3>{{ $product->product_type }}
                    </div>
                    <div class="block-header">
                        <h3 class="block-title" >TOTAL VARIANTS </h3>{{ $product->shopify_varients->count() }}
                    </div>
                    <div class="block-header">
                        <h3 class="block-title" >VENDOR </h3>{{ $product->vendor}}
                    </div>
                </div>

                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">PRODUCT DESCRIPTION</h1>
                    </div>
                    <div class="block-content">
                        <p class="font-size-sm text-muted" >
                            {!! $product->body_html !!}
                        </p>
                    </div>
                </div>

                <div class="block">
                    <div class="block-header">
                        <h1 class="block-title">ADDED VENDORS</h1>
                    </div>
                    <div class="block-content">
                        @if(count($vendor_details)>0)
                            <table class="table table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th>Vendor name</th>
                                    <th>Product Price</th>
                                    <th>Product link</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($vendor_details as $details)
                                    <tr>
                                        <td class="font-w600">
                                            {{ $details->vendor_name }}
                                        </td>

                                        <td class="font-w600">
                                            {{ $details->product_price }}
                                        </td>

                                        <td class="font-w600 ">
                                            <a href="{{ $details->product_link }}" target=_blank" >View Product</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        @else
                            <p>No data!</p>
                        @endif
                    </div>
                </div>
            </div>

            <form class="col-md-12" method="POST" action="{{ route('admin.add.product.vendor', $product->id) }}">
                @csrf
                <div class="block mt-3">
                    <div class="block-header">
                        <h3 class="block-title">Add Vendors</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                        @if(count($vendors)>0)
                        <table class="table table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th>Vendor name</th>
                                <th>Product Price</th>
                                <th>Product link</th>
                                <th>Notes</th>
                            </tr>
                            </thead>
                            <tbody>

                                @foreach($vendors as $vendor)
                                    <tr>
                                        <td class="font-w600">
                                            {{ $vendor->name }}
                                            <input type="hidden" class="form-control" name="vendor_id[]" value="{{ $vendor->id }}">
                                        </td>

                                        <td class=" ">
                                            <input type="text" class="form-control" placeholder="Enter product price" name="product_price[]">
                                        </td>

                                        <td class=" ">
                                            <input type="text" class="form-control" placeholder="Enter product link" name="product_link[]">
                                        </td>

                                        <td class=" ">
                                            <textarea type="text" class="form-control" placeholder="Enter notes" name="product_notes[]"></textarea>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                        @else
                            No data!
                        @endif
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- END Page Content -->




@endsection

