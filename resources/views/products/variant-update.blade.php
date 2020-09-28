@extends('layouts.backend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="{{ asset('css/image-uploader.min.css') }}">
    <style>
        .material-icons{
            display: none !important;

        }

        #custom-text {
            margin-left: 10px;
            font-family: sans-serif;
            color: #aaa;
        }


    </style>
    <script src="{{ asset('js/plugins/ckeditor/ckeditor.js') }}"></script>


@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>b

    <!-- Page JS Helpers (Slick Slider Plugin) -->
    <script>jQuery(function(){ One.helpers(['slick', 'ckeditor']); });</script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="{{ asset('css/image-uploader.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.input-images').imageUploader();

            $('.deleteImg').click(function () {
                var id = $(this).attr('id');
                var parent = $(this).parent().parent().parent().parent().parent();

                $.ajax({
                    url: `/delete/variant/image/${id}`,
                    type: 'GET',
                    success: function(res) {
                        var response = res.data;
                        if(res.data == 'success') {
                            parent.hide();
                        }
                    }
                });
            });
        });
    </script>




@endsection


@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Edit Variant
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/products">Products</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('update.product.variant', $variant->id) }}">Update Variant</a>
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
            <form action="{{ route('edit.product.variant', $variant->id) }}" method="POST" class="row justify-content-center" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-md-12">
                    <div class="block">

                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">Title</label>
                                            <input type="text" class="form-control @error('variant_title') is-invalid @enderror" id="" name="variant_title" placeholder="Enter variant title.." value="{{ $variant->variant_title }}">
                                            @error('variant_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="">Images</label>
                                                    @if($variant->image)
                                                        <div class="row gutters-tiny items-push js-gallery push">
                                                            <div class="col-6 mx-auto animated fadeIn">
                                                                <div class="options-container fx-item-rotate-r">
                                                                    <img class="img-fluid options-item" src="{{ asset('storage/'.$variant->image) }}" alt="">
                                                                    <div class="options-overlay bg-black-75">
                                                                        <div class="options-overlay-content">

                                                                            <a class="btn btn-sm btn-primary img-lightbox" href="{{ asset('storage/'.$variant->image) }}">
                                                                                <i class="fa fa-search-plus mr-1"></i> View
                                                                            </a>
                                                                            <a class="btn btn-sm btn-danger img-lightbox text-white deleteImg" id="{{$variant->id}}">
                                                                                <i class="fa fa-trash-alt mr-1"></i> Delete
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                            <div class="input-images"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Price</label>
                                                    <input type="text" class="form-control @error('variant_price') is-invalid @enderror" id="" name="variant_price" placeholder="Enter variant price.." value="{{ $variant->variant_price }}">
                                                    @error('variant_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Quantity</label>
                                                    <input type="text" class="form-control @error('variant_qty') is-invalid @enderror" id="" name="variant_qty" placeholder="Enter variant quantity.." value="{{ $variant->variant_qty }}">
                                                    @error('variant_qty')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">SKU</label>
                                                    <input type="text" class="form-control @error('variant_sku') is-invalid @enderror" id="" name="variant_sku" placeholder="Enter variant sku.." value="{{ $variant->variant_sku }}">
                                                    @error('variant_sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Barcode</label>
                                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="" name="barcode" placeholder="Enter product quantity.." value="{{ $variant->barcode }}">
                                                    @error('barcode')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Update Variant</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
