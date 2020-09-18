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

            $("#tags").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });

            $('.input-images').imageUploader();
        });
    </script>

    <script type="text/javascript">
        var i = 0;
        var arr1 = null;
        var arr2 = null;
        var arr3 = null;

        $('#varient-check').click(function() {

            if($(this).prop("checked")==true){
                addVarient();
                $("#dynamicTable").show();
                $("#add").show();

            }
            if($(this).prop("checked")==false){
                removeAllVarient();
                $("#dynamicTable").empty();
                $('.preview').empty();
                $("#add").hide();

            }

        });

        function removeAllVarient() {
            i=0;
            $('#dynamicTable').html('');
        }

        function addVarient() {
            ++i;

            if(i <= 3) {
                $("#dynamicTable").append(`
                <tr>
                    <td><label class="option">Option${i}</label></td>
                    <td>
                        <select name="key${i}" id="" class="form-control key">
                            <option value="size">Size</option>
                            <option value="color">Color</option>
                            <option value="material">Material</option>
                            <option value="style">Style</option>
                            <option value="title">Title</option>
                        </select>
                    </td>
                    <td><select class="form-control value" id="arr${i}" multiple="multiple" name="value${i}[]"></select></td>

                    <td><button type="button" class="btn btn-danger remove-tr" id="${i}"><i class="fa fa-fw fa-trash-alt"></i></button></td>

                </tr>
                `);

                initSelect();

                $('#arr1').change(function() {
                    $('.preview').show();
                    addToPreview();
                });

                $('#arr2').change(function() {
                    $('.preview').show();
                    addToPreview();
                });

                $('#arr3').change(function() {
                    $('.preview').show();
                    addToPreview();
                });
            }
        }

        function  initSelect() {

            $("#dynamicTable .value").select2({
                tags: true,
                tokenSeparators: [',', ' '],
                width:'100%',
            });
        }

        function addToPreview() {

            if($('#arr2').select2("val")) {
                arr2 = $('#arr2').select2("val");
            }
            else{
                arr2 = 0
            }

            if($('#arr3').select2("val")) {
                arr3 = $('#arr3').select2("val");
            }
            else{
                arr3 = 0
            }

             arr1 = $('#arr1').select2("val");


            if( arr1.length > 0 && arr2.length === undefined  && arr3.length === undefined) {
                $('#var_body').empty();
                for(var j=0; j< arr1.length ; j++) {

                    $('#var_body').append(`
                            <tr>

                                <td>${arr1[j]} <input type="hidden" value="${arr1[j]}" name="var_title[]"></td>
                                <td><input type="text" name="var_price[]" placeholder="$ 0.0" class="form-control"></td>
                                <td><input type="number" name="var_qty[]"  class="form-control"></td>
                                <td><input type="text" name="var_sku[]"  class="form-control"></td>
                                <td><input type="text" name="var_barcode[]"  class="form-control"></td>
                                <td><button type="button" class="btn btn-danger remove-vr">Remove</button></td>
                            </tr>
                    `);
                }
            }
            else if( arr1.length > 0 && arr2.length > 0  && arr3.length === undefined) {
                $('#var_body').empty();
                for(var j=0; j< arr1.length ; j++) {
                    for(var k=0; k< arr2.length ; k++) {
                        $('#var_body').append(`
                            <tr>

                                <td>${arr1[j]}/${arr2[k]} <input type="hidden" value="${arr1[j]}/${arr2[k]}" name="var_title[]"></td>
                                <td><input type="text" name="var_price[]" placeholder="$ 0.0" class="form-control"></td>
                                <td><input type="number" name="var_qty[]"  class="form-control"></td>
                                <td><input type="text" name="var_sku[]"  class="form-control"></td>
                                <td><input type="text" name="var_barcode[]"  class="form-control"></td>
                                <td><button type="button" class="btn btn-danger remove-vr">Remove</button></td>
                            </tr>
                        `);
                    }
                }
            }
            else if(arr1.length > 0 && arr2.length > 0 && arr3.length > 0) {
                $('#var_body').empty();
                for(var j=0; j< arr1.length ; j++) {
                    for(var k=0; k< arr2.length ; k++) {
                        for(var l=0; l<arr3.length ; l++) {
                            $('#var_body').append(`
                                <tr>

                                    <td>${arr1[j]}/${arr2[k]}/${arr3[l]} <input type="hidden" value="$${arr1[j]}/${arr2[k]}/${arr3[l]} " name="var_title[]"></td>
                                    <td><input type="text" name="var_price[]" placeholder="$ 0.0" class="form-control"></td>
                                    <td><input type="number" name="var_qty[]"  class="form-control"></td>
                                    <td><input type="text" name="var_sku[]"  class="form-control"></td>
                                    <td><input type="text" name="var_barcode[]"  class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger remove-vr">Remove</button></td>
                                </tr>
                            `);
                        }
                    }
                }
            }
        }


        $(document).on('click','.remove-vr',function () {
            $(this).parents('tr').remove();
        });

        $(document).on('click','.remove-update-var',function () {
            var parent = $(this).parent().parent();
            var variant_id = $(this).attr('id');
            console.log(parent);

            $.ajax({
                url: `/delete/variant/${variant_id}`,
                type: 'GET',
                success: function(res) {
                    var response = res.data;
                    if(res.data == 'success') {
                        parent.hide();
                    }
                }
            });

        });



        $("#add").click(function(){
            if(i==2) {
                $(this).hide();
            }

            addVarient();
        });

        $(document).on('click', '.remove-tr', function(){
            var id = $(this).attr('id');

            var tr_count = $(this).parents('tr').nextAll().length;

            if(tr_count == 1) {
                var trs = $(this).parents('tr').nextAll();

                for(var j=0; j<trs.length ; j++) {
                    trs.find('.key').attr('name', `key${id}`);
                    trs.find('.value').attr('name', `value${id}[]`);
                    trs.find('.option').html(`Option${id}`);
                    trs.find('.value').attr('id', `arr${id}`);
                }

            }
            else if(tr_count == 2) {
                var trs = $(this).parents('tr').nextAll();


                trs.each(function() {
                   $(this).find('.key').attr('name', `key${id}`);
                   $(this).find('.value').attr('name', `value${id}[]`);
                   $(this).find('.option').html(`Option${id}`);
                   $(this).find('.value').attr('id', `arr${id}`);
                   id++;
                });
            }

            if(i==1) {
                $('#varient-check').prop('checked', false);
                $('#add').hide();
            }

            if(i>1 && i<=3) {
                $('#add').show();
            }


            --i;

            $(this).parents('tr').remove();
        });

        function deleteImg(id) {

            var el = $(event.target);
            var parent = el.parent().parent().parent().parent();

            $.ajax({
                url: `/delete/product/image/${id}`,
                type: 'GET',
                success: function(res) {
                    var response = res.data;
                    if(res.data == 'success') {
                        parent.hide();
                    }
                }
            });
        }

        function removeVarUpdate(id) {
            var el = $(event.target);
            var parent = el.parent().parent();
            console.log(parent);

            $.ajax({
                url: `/delete/variant/${id}`,
                type: 'GET',
                success: function(res) {
                    var response = res.data;
                    if(res.data == 'success') {
                        parent.hide();
                    }
                }
            });
        }

    </script>
@endsection


@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    @isset($prod) Edit Product @else Add a Product @endisset
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
                            <a class="link-fx" href="{{ route('products.create') }}"> @isset($prod) Edit Product @else Add Product @endisset</a>
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
            <form action="@isset($prod) {{ route('products.update', $prod->id) }} @else {{ route('products.store') }} @endisset" method="POST" class="row justify-content-center" enctype="multipart/form-data">
                @csrf
                @if(isset($prod))
                    @method('PUT')
                @endif

                <div class="col-md-12">
                    <div class="block">

                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">Title</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="" name="title" placeholder="Enter product name.." value="{{ isset($prod) ? $prod->title : '' }}">
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea id="js-ckeditor" name="description">
                                                {{ isset($prod) ? $prod->description : '' }}
                                            </textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Images</label>
                                            @isset($prod)
                                                @if($prod->prod_images()->count() > 0)
                                                    <div class="row gutters-tiny items-push js-gallery push">
                                                    @foreach($prod->prod_images()->get() as $image)
                                                            <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
                                                                <div class="options-container fx-item-rotate-r">
                                                                    <img class="img-fluid options-item" src="{{ asset('storage/'.$image->image) }}" alt="">
                                                                    <div class="options-overlay bg-black-75">
                                                                        <div class="options-overlay-content">

                                                                            <a class="btn btn-sm btn-primary img-lightbox" href="{{ asset('storage/'.$image->image) }}">
                                                                                <i class="fa fa-search-plus mr-1"></i> View
                                                                            </a>
                                                                            <a class="btn btn-sm btn-danger img-lightbox text-white" onclick="deleteImg({{$image->id}})">
                                                                                <i class="fa fa-trash-alt mr-1"></i> Delete
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    @endforeach
                                                    </div>
                                                @endif
                                            @endisset
                                            <div class="input-images"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Price</label>
                                                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="" name="price" placeholder="Enter product price.." value="{{ isset($prod) ? $prod->price : '' }}">
                                                    @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Compare at Price</label>
                                                    <input type="text" class="form-control @error('compare_price') is-invalid @enderror" id="" name="compare_price" placeholder="Enter product compare at price.." value="{{ isset($prod) ? $prod->compare_price : '' }}">
                                                    @error('compare_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Tags</label>
                                                    <select class="form-control" multiple="multiple" id="tags" name="tags[]">
                                                        @isset($prod)
                                                            @if($prod->tags)
                                                                @foreach($prod->tag_array as $tag)
                                                                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="">Quantity</label>
                                                    <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="" name="quantity" placeholder="Enter product quantity.." value="{{ isset($prod) ? $prod->quantity : '' }}">
                                                    @error('quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="">Weight</label>
                                                    <input type="text" class="form-control @error('weight') is-invalid @enderror" id="" name="weight" placeholder="Enter product weight.." value="{{ isset($prod) ? $prod->weight : '' }}">

                                                    @error('weight')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="">Unit</label>
                                                    <select name="unit" id="" class="form-control">
                                                        <option value="gm">gm</option>
                                                        <option value="kg">kg</option>
                                                        <option value="mg">mm</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        @isset($product)
                                        <div class="row">
                                            <div class="col-12">
                                            <label for="">Varients</label><br>
                                            <input type="checkbox" id="varient-check" name="varient_check">
                                            <label for="varient-check" class="text-muted">
                                                This product has multiple options, like different sizes or colors
                                            </label>

                                            <table class="table" id="dynamicTable" style="display: none">
                                                <button type="button" name="add" id="add" class="btn btn-success float-right mb-2" style="display: none">Add More <i class="fa fa-fw fa-plus"></i></button>
                                            </table>
                                        </div>
                                        </div>
                                        @endisset

                                        <div class="row preview" style="display: none">
                                            <div class="col-12">
                                                <label for="">Preview</label><br>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td>Varient Title</td>
                                                            <td>Price</td>
                                                            <td>Quantity</td>
                                                            <td>SKU</td>
                                                            <td>Barcode</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="var_body">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        @isset($prod)
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Varients</label><br>
                                                @if($prod->varients()->count() > 0)
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <td></td>
                                                            <td>Varient Title</td>
                                                            <td>Price</td>
                                                            <td>Quantity</td>
                                                            <td>SKU</td>
                                                            <td>Barcode</td>
                                                            <td></td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($prod->varients()->get() as $varient)
                                                                <tr>
                                                                    <input type="hidden" value="{{ $varient->id }}" name="var_id[]">
                                                                    <td>
                                                                        @if($varient->image)
                                                                            <img class="No Image" src="{{ asset('storage/'.$varient->image) }}" alt="" style="width: 100px; height: auto">
                                                                        @else
                                                                            <img class="No Image" src="https://lunawood.com/wp-content/uploads/2018/02/placeholder-image.png" alt="" style="width: 100px; height: auto">
                                                                        @endif

                                                                    </td>
                                                                    <td>{{ $varient->variant_title }}<input type="hidden" value="{{ $varient->variant_title }}" name="var_title[]"></td>
                                                                    <td><input type="text" name="var_price[]" placeholder="$ 0.0" class="form-control" value="{{ $varient->variant_price }}"></td>
                                                                    <td><input type="number" name="var_qty[]"  class="form-control" value="{{ $varient->variant_qty }}"></td>
                                                                    <td><input type="text" name="var_sku[]"  class="form-control" value="{{ $varient->variant_sku }}"></td>
                                                                    <td><input type="text" name="var_barcode[]"  class="form-control" value="{{ $varient->barcode }}"></td>
                                                                    <td class="d-flex" style="font-size: 8px !important;">
                                                                        <a class="btn btn-sm btn-alt-success js-tooltip-enabled" href="{{ route('update.product.variant', $varient->id) }}" data-toggle="tooltip" title="" data-original-title="View">
                                                                            <i class="fa fa-fw fa-pen"></i>
                                                                        </a>
{{--                                                                        <button type="button" onclick="removeVarUpdate({{ $varient->id }})" class="btn btn-danger btn-sm mx-1" id="{{ $varient->id }}">--}}
{{--                                                                            <i class="fa fa-fw fa-trash-alt"></i>--}}
{{--                                                                        </button>--}}
                                                                        <button type="button" class="btn btn-danger btn-sm mx-1 remove-update-var" id="{{ $varient->id }}">
                                                                            <i class="fa fa-fw fa-trash-alt"></i>
                                                                        </button>
{{--                                                                        <a class="btn btn-success" href=""><i class="fa fa-pen-alt"></i></a>--}}
{{--                                                                        <button type="button" class="btn btn-danger" ><i class="fa fa-trash-alt"></i></button>--}}
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    No Variants
                                                @endif
                                            </div>
                                        </div>
                                        @endisset

                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"> {{ isset($prod) ? 'Update Product' : 'Add Product'}}</button>
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
