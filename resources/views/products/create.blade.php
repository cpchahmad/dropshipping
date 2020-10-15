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

        $(".add_vendor_btn").click(function(){
            addVendor($(this));
        });

        $(".add_link_btn").click(function(){
            addLinks($(this));
        });


        function addVendor(btn) {
            console.log(btn.parent().parent().find('#dynamicTable'));
            btn.parent().parent().find('#dynamicTable').append(`
                    <tr>
                        <td class="">
                            <input type="text" class="form-control"  name="vendor_name[]">
                        </td>
                        <td class="">
                            <input type="text" class="form-control"  name="product_price[]">
                        </td>
                        <td class=" ">
                            <input type="text" class="form-control"  name="product_link[]">
                        </td>
                        <td class=" ">
                            <input type="number" class="form-control"  name="moq[]" step="any">
                        </td>
                        <td class=" ">
                            <input type="text" class="form-control" name="leads_time[]">
                        </td>
                    </tr>
                `);
        }

        function addLinks(btn) {
            btn.parent().parent().find('#linksTable').append(`
                   <input type="text" class="form-control mt-3" name="link[]" placeholder="Enter reference link..">
                `);
        }


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
        <div class="content content-full py-2">
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

                                    <div>
                                        <label for="">Reference Links</label>
                                        <div class="d-flex justify-content-end mb-3">
                                            <button type="button"  class="add_link_btn btn btn-primary btn-sm">Add more</button>
                                        </div>

                                        <div id="linksTable">
                                            @isset($prod)
                                                @if($prod->product_links->count() > 0)
                                                    <strong style="font-size: 14px !important">Refrence Links:</strong>
                                                    <ul class="p-0 list-unstyled">
                                                        @foreach($prod->product_links as $link)
                                                            <input type="text" class="form-control mt-3" name="link[]" placeholder="Enter reference link.." value="{{ $link->link }}">
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endif
                                            <input type="text" class="form-control" name="link[]" placeholder="Enter reference link..">
                                        </div>
                                    </div>

                                    <label for="" class="mt-4">Vendors</label>
                                    <div class="d-flex justify-content-end mb-3">
                                        <button type="button"  class="add_vendor_btn btn btn-primary btn-sm">Add more</button>
                                    </div>

                                    <table class="table table-striped table-vcenter">
                                        <thead>
                                        <tr>
                                            <th>Vendor name</th>
                                            <th style="width: 12%;">Product cost</th>
                                            <th>Product link</th>
                                            <th style="width: 8%;">Minimum quantity</th>
                                            <th>Leads time</th>
                                        </tr>
                                        </thead>
                                        <tbody id="dynamicTable">
                                        <tr>
                                            <td class="">
                                                <input type="text" class="form-control"  name="vendor_name[]">
                                            </td>
                                            <td class="">
                                                <input type="text" class="form-control"  name="product_price[]">
                                            </td>
                                            <td class=" ">
                                                <input type="text" class="form-control"  name="product_link[]">
                                            </td>
                                            <td class=" ">
                                                <input type="number" class="form-control"  name="moq[]" step="any">
                                            </td>
                                            <td class=" ">
                                                <input type="text" class="form-control" name="leads_time[]">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

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
