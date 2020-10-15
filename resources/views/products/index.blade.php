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
            $('.delete-vendor-btn').click(function () {
                var id = $(this).attr('id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: `/admin/delete/vendor/${id}`,
                    type: 'DELETE',
                    success: function(res) {
                        var response = res.data;
                        if(response == 'success') {
                            toastr.success("Vendor deleted Successfully!") ;
                            $('li#'+id).html('');
                            $('#deleteModal'+id).modal('hide');
                        }
                        else   if(response == 'error') {
                            toastr.error("Vendor cannot be deleted because it is associated to some line item!") ;
                            $('#deleteModal'+id).modal('hide');
                        }
                    }
                });

            });

            $('.edit-vendor-btn').click(function () {
                var id = $(this).attr('id');
                var name = $(`input[name=name${id}]`).val();
                var link = $(`input[name=link${id}]`).val();
                var moqs = $(`input[name=moqs${id}]`).val();
                var lead_time = $(`input[name=lead_time${id}]`).val();
                var price = $(`input[name=price${id}]`).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: `/admin/edit/vendor/${id}`,
                    data: { name : name, link : link, moqs : moqs, lead_time : lead_time, price : price},
                    type: 'PUT',
                    success: function(res) {
                        var response = res.data;

                        if(response == 'success') {
                            toastr.success("Vendor Updated Successfully!") ;
                            $('li#'+id).find('.name').html(res.vendor.name);
                            $('li#'+id).find('.cost').html(res.vendor.cost);
                            $('li#'+id).find('.leads').html(res.vendor.leads_time);
                            $('li#'+id).find('.url').html(res.vendor.url);
                            $('li#'+id).find('.moq').html(res.vendor.moq);
                            $('#editModal'+id).modal('hide');
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
                            <th class="text-center" style="width: 35%;">Vendors</th>
                            <th class="text-center" style="width: 35%;">Details</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 10%;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td class="">
                                    <div class="text-left">
                                        <img src="{{ $product->image }}" alt="No Image Availble" class="img-fluid hover-img" style="width: 70px; height: auto">
                                    </div>
                                    <div class=" d-flex flex-column" style="font-size: 12px !important;">
                                        <span class="font-weight-bolder" style="font-size: 14px !important;">{{ $product->title}}</span>
                                        <em>{{ $product->created_at->format('M,d,Y') }}</em>
                                    </div>
                                </td>
                                <td class="d-flex border-bottom-0" style="font-size: 14px !important;">
                                    @if($product->product_vendor_details->count()>0)
                                        <ul class="pl-1 w-100">
                                            @php
                                                $counter = 0;
                                            @endphp
                                            @foreach($product->product_vendor_details as $details)
                                                @if($counter == count( $product->product_vendor_details  ) - 1)
                                                    <li class='mb-2 list-unstyled mt-2 d-flex justify-content-between' id="{{ $details->id }}">
                                                        <div class="mb-2">
                                                            <span class="d-block"><span class="font-weight-bold">Vendor name:</span> <span class="name">{{$details->name}}</span></span>
                                                            <span class="d-block"><span class="font-weight-bold">Cost:</span> $<span class="cost">{{number_format($details->cost, 2)}}</span></span>
                                                            <span class="d-block"><span class="font-weight-bold">MOQ:</span> <span class="moq">{{$details->moq}}</span></span>
                                                            <span class="d-block"><span class="font-weight-bold">Lead time:</span> <span class="leads">{{$details->leads_time}}</span></span>
                                                            <a href="{{$details->url }}" target=_blank\" class="url"> View Product </a >
                                                        </div>
                                                        <div class="btn-group align-items-center">
                                                            <button type="button" data-toggle="modal" data-target="#editModal{{ $details->id }}" class="btn btn-success btn-sm" >
                                                                <i class="fa fa-fw fa-pen-alt"></i>
                                                            </button>

                                                            <button type="button" data-toggle="modal" data-target="#deleteModal{{ $details->id }}" class="btn btn-danger btn-sm" >
                                                                <i class="fa fa-fw fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class='mb-2 list-unstyled border-bottom d-flex justify-content-between' id="{{ $details->id }}">
                                                        <div class="mb-2">
                                                            <span class="d-block"><span class="font-weight-bold">Vendor name:</span> <span class="name">{{$details->name}}</span></span>
                                                            <span class="d-block"><span class="font-weight-bold">Cost:</span> $<span class="cost">{{number_format($details->cost, 2)}}</span></span>
                                                            <span class="d-block"><span class="font-weight-bold">Minimum amount of quantity:</span> <span class="moq">{{$details->moq}}</span></span>
                                                            <span class="d-block"><span class="font-weight-bold">Lead time:</span> <span class="leads">{{$details->leads_time}}</span></span>
                                                            <a href="{{$details->url }}" target=_blank\" class="url"> View Product </a >
                                                        </div>
                                                        <div class="btn-group align-items-center">
                                                            <button type="button" data-toggle="modal" data-target="#editModal{{ $details->id }}" class="btn btn-success btn-sm" >
                                                                <i class="fa fa-fw fa-pen-alt"></i>
                                                            </button>

                                                            <button type="button" data-toggle="modal" data-target="#deleteModal{{ $details->id }}" class="btn btn-danger btn-sm" >
                                                                <i class="fa fa-fw fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                @endif

                                                <div class="modal fade" id="deleteModal{{$details->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are going to delete the vendor
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <form action="{{ route('admin.delete.product.vendor', $details->id) }}" method="POST" >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-danger delete-vendor-btn" id="{{ $details->id }}">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="editModal{{$details->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog"  style="max-width: 70% !important;" role="document">
                                                        <div class="modal-content">
                                                            <div class="block block-themed block-transparent mb-0">
                                                                <div class="block-header bg-primary-dark">
                                                                    <h3 class="block-title">Edit vendor details for {{ $product->title }}</h3>
                                                                    <div class="block-options">
                                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                            <i class="fa fa-fw fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <form class="col-md-12" method="POST" action="{{ route('admin.edit.product.vendor', $product->id) }}">
                                                                    @csrf
                                                                    <div class="block mt-3">

                                                                        <div class="block-content block-content-full">

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
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="">
                                                                                        <input type="text" class="form-control"  name="name{{$details->id}}" value="{{ $details->name}}">
                                                                                    </td>
                                                                                    <td class="">
                                                                                        <input type="text" class="form-control"  name="price{{$details->id}}" value="{{number_format($details->cost, 2)}}">
                                                                                    </td>
                                                                                    <td class=" ">
                                                                                        <input type="text" class="form-control"  name="link{{$details->id}}" value="{{ $details->url}}">
                                                                                    </td>
                                                                                    <td class=" ">
                                                                                        <input type="number" class="form-control"  name="moqs{{$details->id}}" step="any" value="{{ $details->moq}}">
                                                                                    </td>
                                                                                    <td class=" ">
                                                                                        <input type="text" class="form-control" name="lead_time{{$details->id}}" value="{{ $details->leads_time}}"}>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <div class="d-flex justify-content-end">
                                                                                <button type="button" class="btn btn-primary edit-vendor-btn" id="{{ $details->id }}">Edit</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @php
                                                    $counter++;
                                                @endphp
                                            @endforeach

                                        </ul>
                                    @else
                                        <p>No data!</p>
                                    @endif
                                </td>
                                <td class="" style="font-size: 12px !important;">
                                    <span><strong style="font-size: 14px !important">Description:</strong>{!! $product->description  !!}</span>
                                    @if($product->product_links->count() > 0)
                                        <strong style="font-size: 14px !important">Refrence Links:</strong>
                                        <ul class="p-0 list-unstyled">
                                            @foreach($product->product_links as $link)
                                                <li><a href="{{ $link->link }}" target="_blank">{{ $link->link }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                                <td class="text-center" style="font-size: 14px !important;">
                                    {{ $product->approved_status }}
                                    @if($product->notes)
                                        <p class="text-left"><strong>Comments: </strong>{{ $product->notes }}</p>
                                    @endif
                                </td>
                                <td class="text-center justify-content-center">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-success" href="{{ route('products.edit', $product->id) }}" data-toggle="tooltip" title="" data-original-title="View">
                                            <i class="fa fa-fw fa-pen"></i>
                                        </a>
                                        <button type="button" data-toggle="modal" data-target="#deleteModal{{ $product->id }}" class="btn btn-danger btn-sm" >
                                            <i class="fa fa-fw fa-trash-alt"></i>
                                        </button>
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
