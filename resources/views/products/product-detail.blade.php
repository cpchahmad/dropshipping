@extends('layouts.backend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('/js/plugins/dropzone/dist/min/dropzone.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <style>

        #custom-text {
            font-family: sans-serif;
            color: #aaa;
        }

    </style>

@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/dropzone/dropzone.min.js') }}"></script>

    <!-- Page JS Helpers (Slick Slider Plugin) -->
    <script>jQuery(function(){ One.helpers('slick'); });</script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#smart_collection').select2();
            $('#custom_collection').select2();

        });

        const realFileBtn = $(".real-file");
        const customBtn = $(".custom-button");
        const customTxt = document.getElementById("custom-text");

        customBtn.addEventListener("click", function() {
            realFileBtn.click();
        });

        realFileBtn.addEventListener("change", function() {
            if (realFileBtn.value) {
                customTxt.innerHTML = realFileBtn.value.match(
                    /[\/\\]([\w\d\s\.\-\(\)]+)$/
                )[1];
            } else {
                customTxt.innerHTML = "No file chosen, yet.";
            }
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
                        @role('admin')
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/admin/outsource/products">Pending Products</a>
                        </li>
                        @endrole
                        @role('outsource_team')
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('products.index') }}">Products</a>
                        </li>
                        @endrole

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
                <div class="col-md-6">
                    <!-- Slider with dots -->
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Product Image</h3>
                        </div>
                        @if($product->prod_images()->count() > 0)
                            <div class="row gutters-tiny items-push js-gallery push pb-3 pl-3">
                                @foreach($product->prod_images()->get() as $image)
                                    <div class="col-6 animated fadeIn">
                                        <div class="options-container fx-item-rotate-r">
                                            <img class="img-fluid options-item" src="{{ asset('storage/'.$image->image) }}" alt="">
                                            <div class="options-overlay bg-black-75">
                                                <div class="options-overlay-content">

                                                    <a class="btn btn-sm btn-primary img-lightbox" href="{{ asset('storage/'.$image->image) }}">
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
                            <h1 class="block-title">APPROVED </h1><span class="badge {{ $product->approved_class }}">{{ $product->approved_status }}</span>
                        </div>
                        <div class="block-header">
                            <h1 class="block-title">TITLE </h1>{{ $product->title }}
                        </div>
                        <div class="block-header">
                            <h3 class="block-title" >PRICE </h3>{{ $product->price }}
                        </div>
                        <div class="block-header">
                            <h3 class="block-title" >COMPARE AT PRICE </h3>{{ $product->compare_price }}
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <h1 class="block-title">PRODUCT DESCRIPTION</h1>
                        </div>
                        <div class="block-content">
                            <p class="font-size-sm text-muted" >
                                {!! $product->description !!}
                            </p>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <h1 class="block-title">QUANTITY </h1>{{ $product->quantity }}
                        </div>
                        <div class="block-header">
                            <h3 class="block-title" >WEIGHT </h3>{{ $product->weight }}{{ $product->unit }}
                        </div>
                        <div class="block-header">
                            <h3 class="block-title" >TAGS </h3>{{ $product->product_tags }}
                        </div>
                    </div>

                </div>

                <form class="col-md-12" method="POST" action="{{ route('add.images', $product->id) }}"  enctype="multipart/form-data">
                    @csrf
                    <div class="block mt-3">
                        <div class="block-header">
                            <h3 class="block-title">Variants</h3>
                        </div>
                        @if(count($product->varients) > 0)
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                                <table class="table table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Variant Title</th>
                                        <th>Variant Price</th>
                                        <th>Variant SKU</th>
                                        <th>Variant Barcode</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($product->varients as $varient)
                                        <tr>
                                            <td>
                                                @role('outsource_team')
                                                    @if($varient->image)
                                                        <img src="{{ asset('storage/'.$varient->image) }}" alt="No Image Availble" style="width: 100px; height: auto">
                                                    @else
                                                        <input type="file" name="var_img[]" class="@error('var_img') is-invalid @enderror">
                                                        @error('var_img')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <input type="hidden" value="{{ $varient->id }}" name="var_id">
                                                    @endif
                                                @endrole

                                                @role('admin')
                                                @if($varient->image)
                                                    <img src="{{ asset('storage/'.$varient->image) }}" alt="No Image Availble" style="width: 100px; height: auto">
                                                @else
                                                    No image provided yet!
                                                @endif
                                                @endrole
                                            </td>
                                            <td class="font-w600">
                                                {{ $varient->variant_title }}
                                            </td>

                                            <td class="font-w600">
                                                {{ $varient->variant_price }}
                                            </td>

                                            <td class="font-w600">
                                                {{ $varient->variant_sku }}
                                            </td>

                                            <td class="font-w600">
                                                {{ $varient->barcode }}
                                            </td>
                                            <td>
                                                @role('outsource_team')
                                                    @if($varient->image)

                                                    @else
                                                        <button type="submit" class="btn btn-primary">Add Image</button>
                                                    @endif
                                                @endrole

                                            </td>

                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                            @else
                                No data!
                            @endif
                        </div>
                    </div>
                </form>

                @role('admin')
                <div class="col-md-12 text-right mb-3">
                    @if($product->approved == 1)
                        <a href="{{ route('admin.reject.products', $product->id) }}" class="btn btn-danger">Reject<i class="fa fa-times text-white ml-2"></i></a>
                    @else
                        <a href="{{ route('admin.approve.products', $product->id) }}" class="btn btn-success">Approve<i class="fa fa-check text-white ml-2"></i></a>
                    @endif
                </div>
                @endrole
            </div>
        </div>
    </div>
    <!-- END Page Content -->




@endsection

