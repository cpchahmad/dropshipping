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
        $(document).ready(function() {
            $('.rejectBtn').click(function (event) {
                event.preventDefault();
                var id = $(this).attr('id');
                $('#approvalForm').attr('action', `/admin/approve/products/${id}`);
                $('#approvalForm').submit();

            });

            $('.approveBtn').click(function (event) {
                event.preventDefault();
                var id = $(this).attr('id');
                $('#approvalForm').attr('action', `/admin/reject/products/${id}`);
                $('#approvalForm').submit();

            });
        });
    </script>
@endsection

@section('content')

    <div class="bg-body-light">
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                <h1 class="flex-sm-fill h3 my-2">
                    Products
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('products.index') }}">Products</a>
                        </li>
                    </ol>
                </nav>
            </div>
       </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <form class="js-form-icon-search push" action="" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search by Product Title" value="{{$search}}" name="search" required >
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href="/admin/products"> <i class="fa fa-times"></i> Clear </a>
                    </div>
                </div>
            </div>
        </form>

        @role('outsource_team')
        <div class="row mt-1 d-flex justify-content-end mr-1">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
        </div>
        @endrole



        <div class="block">
            <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#shopify_products">Shopify Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#source_products">Source Team Products</a>
                </li>
            </ul>
            <div class="block-content tab-content overflow-hidden">
                <div class="tab-pane fade show active" id="shopify_products" role="tabpanel">
                    <!-- Shopify Product Table Full -->
                    <div class="block mt-3" id="">
                        <div class="block-header">
                            <h3 class="block-title">Shopify Products</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                            @if(count($products)>0)
                                <table class="table table-striped table-vcenter table-bordered w-75">
                                    <thead>
                                    <tr>
                                        <th class="text-left" style="width: 10px">Product</th>
                                        <th class="text-left" style="width: 260px">Variants</th>
                                        <th class="text-left" style="width: 20px">Details</th>
                                        <th class="text-center" style="width: 5px">Add Vendor</th>
                                        <th class="text-left" style="width: 50px">Added Vendors</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="">
                                                <div class="text-left pt-3">
                                                    <a href="{{ $product->img }}" target="_blank">
                                                        <img src="{{ $product->img }}" alt="No Image Availble" style="width: 90px; height: auto">
                                                    </a>
                                                </div>
                                                <div class=" d-flex flex-column " style="font-size: 12px !important;">
                                                    <span class="font-weight-bolder" style="font-size: 14px !important;">{{ $product->title}}</span>
                                                    <em>{{ $product->date }}</em>
                                                </div>
                                            </td>
                                            <td class="" style="font-size: 12px !important;">
                                                {{ $product->variant_details}}
                                            </td>
                                            <td class="" style="font-size: 12px !important;">
                                                <div class="w-75">
                                                    <span class="w-50"><strong style="font-size: 14px !important">Description:</strong>{!! $product->body_html  !!}</span><br>
                                                </div>
                                                <span><strong style="font-size: 14px !important">Vendor:</strong> {{ $product->vendor }}</span><br>
                                                <span><strong style="font-size: 14px !important">Type:</strong> {{ $product->product_type }}</span><br>
                                                <span><strong style="font-size: 14px !important">Tags:</strong> {{ $product->tags }}</span><br>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-light push" data-toggle="modal" data-target="#addModal{{$product->id}}">Add Vendor</button>

                                                <div class="modal p-0" id="addModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="block block-themed block-transparent mb-0">
                                                                <div class="block-header bg-primary-dark">
                                                                    <h3 class="block-title">Add vendor details for {{ $product->title }}</h3>
                                                                    <div class="block-options">
                                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                            <i class="fa fa-fw fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <form class="col-md-12" method="POST" action="{{ route('admin.add.product.vendor', $product->id) }}">
                                                                    @csrf
                                                                    <div class="block mt-3">
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
                                                </div>
                                            </td>

                                            <td class="d-flex border-0" style="font-size: 14px !important;">
                                                @if(($product->vendor_count)>0)
                                                    <ul class="pl-3">
                                                        {{ $product->vendor_detail }}
                                                    </ul>
                                                @else
                                                    <p>No data!</p>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No data!</p>
                            @endif

                            <div class="d-flex justify-content-end">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                    <!-- Shopify Product Table Full -->

                </div>
                <div class="tab-pane fade" id="source_products" role="tabpanel">
                    <!-- Source Product Table Full -->
                    <div class="block mt-3" id="source_products">
                        <div class="block-header">
                            <h3 class="block-title">Source Teams Products</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                            @if(count($prods)>0)
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 15%;">Product</th>
                                        <th class="text-center" style="width: 40%;">Variants</th>
                                        <th class="text-center" style="width: 40%;">Details</th>
                                        <th class="text-center" style="width: 5%;">Approve/Approved</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($prods as $product)
                                        <tr>
                                            <td class="">
                                                <div class="text-left ">
                                                    <img src="{{ $product->image }}" alt="No Image Availble" style="width: 70px; height: auto">
                                                </div>
                                                <div class=" d-flex flex-column" style="font-size: 12px !important;">
                                                    <span class="font-weight-bolder" style="font-size: 14px !important;">{{ $product->title}}</span>
                                                    <em>{{ $product->created_at->format('d/m/Y') }}</em>
                                                </div>
                                            </td>
                                            <td class="font-w600">
                                                {{ $product->variant_details}}
                                            </td>
                                            <td class="" style="font-size: 12px !important;">

                                                 <span><strong style="font-size: 14px !important">Description:</strong>{!! $product->description  !!}</span><br>
                                                 <span><strong style="font-size: 14px !important">Source Team:</strong> {{ $product->source_name }}</span><br>
                                                 <span><strong style="font-size: 14px !important">Price:</strong> ${{ $product->price }}</span><br>
                                                 <span><strong style="font-size: 14px !important">Compare at Price:</strong> ${{ $product->compare_price }}</span><br>
                                                 <span><strong style="font-size: 14px !important">Tags:</strong> {{ $product->product_tags }}</span><br>
                                                 <span><strong style="font-size: 14px !important">Weight:</strong> {{ $product->weight }} {{ $product->unit }}</span><br>
                                                 <span><strong style="font-size: 16px !important">Approved Status:</strong>{{ $product->approved_status }}</span><br>
                                            </td>

                                            <td class="text-center" style="font-size: 12px !important;">
                                                <button type="button" class="btn btn-sm btn-primary push" data-toggle="modal" data-target="#addNotesModal{{$product->id}}">Change Status</button>

                                                <div class="modal" id="addNotesModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="block block-themed block-transparent mb-0">
                                                                    <div class="block-header bg-primary-dark">
                                                                        <h3 class="block-title">Give some notes about {{ $product->title }}</h3>
                                                                        <div class="block-options">
                                                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                                <i class="fa fa-fw fa-times"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <form action="" method="POST" id="approvalForm">
                                                                        @csrf
                                                                        <div class="block-content font-size-sm pb-2">
                                                                            <h5>Notes</h5>
                                                                            <textarea name="notes" id="" cols="30" rows="10" class="form-control" placeholder="Enter some notes regarding the product"></textarea>
                                                                        </div>
                                                                        <div class="block-content block-content-full text-right">
                                                                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-success rejectBtn" id="{{ $product->id }}">Approve<i class="fa fa-check text-white ml-2"></i></button>
                                                                            <button type="submit" class="btn btn-danger approveBtn" id="{{ $product->id }}">Reject<i class="fa fa-times text-white ml-2"></i></button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <div class="d-flex justify-content-end">
                                        {{ $prods->links() }}
                                    </div>
                                </table>
                            @else
                                <p>No data!</p>
                            @endif





                        </div>
                    </div>
                    <!-- Source Product Table Full -->
                </div>
            </div>
    </div>
    <!-- END Page Content -->
@endsection
