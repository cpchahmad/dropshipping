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

        @role('outsource_team')
            <div class="row mt-1 d-flex justify-content-end mr-1">
                <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
            </div>
        @endrole


{{--        <!-- Dynamic Table Full -->--}}
{{--        <div class="block mt-3">--}}
{{--            <div class="block-header">--}}
{{--                <h3 class="block-title">Products Table</h3>--}}
{{--            </div>--}}
{{--            <div class="block-content block-content-full">--}}
{{--                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->--}}
{{--                @if(count($products)>0)--}}
{{--                <table class="table table-striped table-vcenter">--}}
{{--                    <thead>--}}
{{--                        <tr>--}}
{{--                            <th class="d-none d-sm-table-cell" style="width: 15%;"></th>--}}
{{--                            <th>Title</th>--}}
{{--                            @role('outsource_team')--}}
{{--                            <th>Approved</th>--}}
{{--                            @endrole--}}
{{--                            <th style="width: 15%;"></th>--}}
{{--                        </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                        @foreach($products as $product)--}}
{{--                        <tr>--}}
{{--                            @role('admin')--}}
{{--                            <td class="d-none d-sm-table-cell">--}}
{{--                                <img src="{{ $product->img }}" alt="No Image Availble" style="width: 100px; height: auto">--}}
{{--                            </td>--}}
{{--                            <td class="font-w600">--}}
{{--                                <a href="{{ route('admin.products.details', $product->id) }}">{{ $product->title}}</a>--}}
{{--                            </td>--}}

{{--                            <td class="">--}}
{{--                                <a class="btn btn-sm btn-alt-primary js-tooltip-enabled float-right" href="{{ route('admin.products.details', $product->id) }}" data-toggle="tooltip" title="" data-original-title="View">--}}
{{--                                    <i class="fa fa-fw fa-eye"></i>--}}
{{--                                </a>--}}
{{--                            </td>--}}
{{--                            @endrole--}}
{{--                            @role('outsource_team')--}}
{{--                            <td class="d-none d-sm-table-cell">--}}
{{--                                <img src="{{ asset('storage/'.$product->image) }}" alt="No Image Availble" style="width: 100px; height: auto">--}}
{{--                            </td>--}}
{{--                            <td class="font-w600">--}}
{{--                                <a href="{{ route('products.show', $product->id) }}">{{ $product->title}}</a>--}}
{{--                            </td>--}}
{{--                            <td class="font-w600">--}}

{{--                            </td>--}}
{{--                            <td class="">--}}
{{--                                <div class="row justify-content-end mr-2">--}}
{{--                                    <a class="btn btn-sm btn-alt-success js-tooltip-enabled" href="{{ route('products.edit', $product->id) }}" data-toggle="tooltip" title="" data-original-title="View">--}}
{{--                                        <i class="fa fa-fw fa-pen"></i>--}}
{{--                                    </a>--}}

{{--                                    <button type="button" data-toggle="modal" data-target="#deleteModal{{ $product->id }}" class="btn btn-danger btn-sm mx-1" >--}}
{{--                                        <i class="fa fa-fw fa-trash-alt"></i>--}}
{{--                                    </button>--}}

{{--                                    <a class="btn btn-sm btn-alt-primary js-tooltip-enabled" href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" title="" data-original-title="View">--}}
{{--                                        <i class="fa fa-fw fa-eye"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}

{{--                            </td>--}}
{{--                            <!-- Delete modal -->--}}
{{--                            <div class="modal fade" id="deleteModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--                                <div class="modal-dialog" role="document">--}}
{{--                                    <div class="modal-content">--}}
{{--                                        <div class="modal-header">--}}
{{--                                            <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>--}}
{{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                <span aria-hidden="true">&times;</span>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-body">--}}
{{--                                            You are going to delete the product--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-footer">--}}
{{--                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" >--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="btn btn-danger">Delete</button>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            @endrole--}}


{{--                        </tr>--}}
{{--                        @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--                @else--}}
{{--                    <p>No data!</p>--}}
{{--                @endif--}}

{{--                <div class="d-flex justify-content-end">--}}
{{--                    {{ $products->links() }}--}}
{{--                </div>--}}



{{--            </div>--}}
{{--        </div>--}}
{{--        <!-- END Dynamic Table Full -->--}}

        <!-- Source Product Table Full -->
        <div class="block mt-3" id="source_products">
            <div class="block-header">
                <h3 class="block-title">Your Products</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                @if(count($products)>0)
                    <table class="table table-striped table-vcenter table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">Product</th>
                            <th class="text-center" style="width: 40%;">Variants</th>
                            <th class="text-center" style="width: 35%;">Details</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 5%;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class="">
                                    <div class="text-left">
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
                                    <span><strong style="font-size: 14px !important">Outsource Team:</strong> {{ $product->source_name }}</span><br>
                                    <span><strong style="font-size: 14px !important">Price:</strong> ${{ $product->price }}</span><br>
                                    <span><strong style="font-size: 14px !important">Compare at Price:</strong> ${{ $product->compare_price }}</span><br>
                                    <span><strong style="font-size: 14px !important">Tags:</strong> {{ $product->product_tags }}</span><br>
                                    <span><strong style="font-size: 14px !important">Weight:</strong> {{ $product->weight }} {{ $product->unit }}</span><br>
                                </td>

                                <td class="text-center" style="font-size: 14px !important;">
                                    {{ $product->approved_status }}
                                    <p class="text-left">{{ $product->notes }}</p>
                                </td>
                                <td class="">
                                    <div class="d-flex">
                                        <a class="btn btn-sm btn-alt-success js-tooltip-enabled" href="{{ route('products.edit', $product->id) }}" data-toggle="tooltip" title="" data-original-title="View">
                                            <i class="fa fa-fw fa-pen"></i>
                                        </a>
                                        <button type="button" data-toggle="modal" data-target="#deleteModal{{ $product->id }}" class="btn btn-danger btn-sm mx-1" >
                                            <i class="fa fa-fw fa-trash-alt"></i>
                                        </button>

{{--                                        <a class="btn btn-sm btn-alt-primary js-tooltip-enabled" href="{{ route('products.show', $product->id) }}" data-toggle="tooltip" title="" data-original-title="View">--}}
{{--                                            <i class="fa fa-fw fa-eye"></i>--}}
{{--                                        </a>--}}
                                    </div>

                                </td>
                                <!-- Delete modal -->
                                <div class="modal fade" id="deleteModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                You are going to delete the product
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
        <!-- Source Product Table Full -->

    </div>
    <!-- END Page Content -->
@endsection
