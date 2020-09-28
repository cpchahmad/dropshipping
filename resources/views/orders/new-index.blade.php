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
            $(".status_select").change(function(){
                var id = $(this).find("option:selected").val();

                switch (id){
                    case "Fulfilled":
                        $('.tracking').show();
                        break;


                }
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
    <div class="content">

        <form class="js-form-icon-search push" action="" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search by Order ID" value="{{$search}}" name="search" required >
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href="/admin/orders"> <i class="fa fa-times"></i> Clear </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="bg-white p-3 push">
            <!-- Navigation -->
            <div id="horizontal-navigation-hover-normal" class="d-none d-lg-block mt-2 mt-lg-0">
                <ul class="nav-main nav-main-horizontal nav-main-hover">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($status == 'unfulfilled') active @endif" href="?status=unfulfilled">
                            <i class="nav-main-link-icon fa fa-flag-checkered"></i>
                            <span class="nav-main-link-name">Unfulfilled</span>
                            <span class="nav-main-link-badge badge badge-pill badge-warning">{{$all_orders->where('fulfillment_status', null)->count()}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($status == 'fulfilled') active @endif " href="?status=fulfilled">
                            <i class="nav-main-link-icon fa fa-star"></i>
                            <span class="nav-main-link-name">Fulfilled</span>
                            <span class="nav-main-link-badge badge badge-pill badge-success">{{$all_orders->whereIN('fulfillment_status',['fulfilled'])->count()}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($status == 'completed') active @endif " href="?status=completed">
                            <i class="nav-main-link-icon fa fa-check-circle"></i>
                            <span class="nav-main-link-name">Completed</span>
                            <span class="nav-main-link-badge badge badge-pill " style="background: darkslategray;color: white;">{{$all_orders->whereIN('fulfillment_status',['completed'])->count()}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($status == 'cancelled') active @endif " href="?status=cancelled">
                            <i class="nav-main-link-icon fa fa-times-circle"></i>
                            <span class="nav-main-link-name">Cancelled</span>
                            <span class="nav-main-link-badge badge badge-pill badge-danger" >{{$all_orders->whereIN('fulfillment_status',['cancelled'])->count()}}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END Navigation -->
        </div>

        <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-header">
                <h3 class="block-title">Orders</h3>
            </div>
            <div class="block-content block-content-full">
                @if(count($orders)> 0)
                    <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">Order</th>
                            <th class="">Products</th>
                            <th class="text-center" style="width: 120px;">Status</th>
                            <th class="" style="width: 120px;">Shipping Method</th>
                            <th class="" style="width: 150px;">Shipping Address</th>
                            <th class="text-center" style="width: 40px;">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="" style="font-size: 12px !important;">
                                <a class="d-block font-weight-bold" style="font-size: 14px !important;">#{{ $order->name }}</a>
                                {{ $order->date }}
                            </td>
                            <td class="" style="font-size: 12px !important;">
                                <span class="text-left font-w400 text-uppercase badge badge-dark">{{ $order->fulfillment }}</span>
                                @role('admin')
                                    <span class="text-left font-weight-bold text-uppercase ml-4" style="font-size: 15px;">${{ $order->total_price }}</span>
                                @endrole
{{--                                {{ $order->line_details }}--}}
                                    @foreach($order->line_items()->get() as $item)
                                        <form class='row d-flex align-items-center py-2 border-bottom' action="{{ route('admin.store.order.vendor') }}" method="POST">
                                            @csrf
                                            <div class="col-2">
                                                <a href="{{ $item->img }}" target="_blank">
                                                    <img src='{{ $item->img }}' alt='No img' class="img-fluid" style='width: 80%; height: auto;'>
                                                </a>
                                            </div>
                                            <div class=' col-6'>
                                                <span class="d-block font-weight-lighter">{{$item->title}}</span>
                                                <span class="d-block font-weight-lighter"><span class='font-weight-bold'>SKU: </span> {{$item->sku}}</span>
                                                @if($order->ful_check)
                                                    <span class="d-block font-weight-bolder">Vendors: </span>
                                                    <input type="hidden" value="{{ $item->id }}" name="line[]">
                                                    {{ $item->vendors }}
                                                @endif
                                            </div>
                                            <div class="text-right col-4">
                                                <p class="font-weight-bold">x{{$item->quantity}}</p>
                                                @if($order->ful_check && $item->vendor_chk)
                                                    <button type="submit" class="btn btn-dark btn-sm">Save</button>
                                                @endif
                                            </div>
                                        </form>
                                    @endforeach

                            </td>
                            <td class="" style="font-size: 12px !important;">
                                <button type="button" class="btn btn-sm btn-light push" data-toggle="modal" data-target="#updateModal{{$order->id}}">Change Status</button>

                                <div class="modal" id="updateModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Select Status Order {{ $order->name }}</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{ route('admin.change.order.status', $order->id) }}" method="POST">
                                                    @csrf
                                                    <div class="block-content font-size-sm pb-2">
                                                            <select name="status" class="form-control status_select">
                                                                <option value="Unfulfilled">Unfulfilled</option>
                                                                <option value="Fulfilled" >Fulfilled</option>
                                                            </select>
                                                    </div>

                                                    <div class="block-content font-size-sm pb-2 tracking" style="display: none">
                                                        <h5>Tracking number</h5>
                                                        <input type="text" name="tracking_number" class="form-control mb-2" placeholder="Enter tracking number..">
                                                        <input type="text" name="tracking_url" class="form-control mb-2" placeholder="Enter tracking url..">
                                                        <select name="shipping_carrier" class="form-control status_select">
                                                            <option value="null" selected disabled>Select carrier</option>
                                                            <option value="4PX">4PX</option>
                                                            <option value="APC">APC</option>
                                                            <option value="Amazon Logistics UK">Amazon Logistics UK</option>
                                                            <option value="Amazon Logistics US">Amazon Logistics US</option>
                                                            <option value="Anjun Logistics">Anjun Logistics</option>
                                                            <option value="Australia Post">Australia Post</option>
                                                            <option value="Bluedart">Bluedart</option>
                                                            <option value="Canada Post">Canada Post</option>
                                                            <option value="Canpar">Canpar</option>
                                                            <option value="China Post">China Post</option>
                                                            <option value="Chukou1">Chukou1</option>
                                                            <option value="Correios">Correios</option>
                                                            <option value="Couriers Please">Couriers Please</option>
                                                            <option value="DHL Express">DHL Express</option>
                                                            <option value="DHL eCommerce">DHL eCommerce</option>
                                                            <option value="DHL eCommerce Asia">DHL eCommerce Asia</option>
                                                            <option value="DPD">DPD</option>
                                                            <option value="DPD Local">DPD Local</option>
                                                            <option value="DPD UK">DPD UK</option>
                                                            <option value="Delhivery">Delhivery</option>
                                                            <option value="Eagle">Eagle</option>
                                                            <option value="FSC">FSC</option>
                                                            <option value="Fastway Australia">Fastway Australia</option>
                                                            <option value="FedEx">FedEx</option>
                                                            <option value="GLS">GLS</option>
                                                            <option value="GLS (US)">GLS (US)</option>
                                                            <option value="Globegistics">Globegistics</option>
                                                            <option value="Japan Post (EN)">Japan Post (EN)</option>
                                                            <option value="Japan Post (JA)">Japan Post (JA)</option>
                                                            <option value="La Poste">La Poste</option>
                                                            <option value="New Zealand Post">New Zealand Post</option>
                                                            <option value="Newgistics">Newgistics</option>
                                                            <option value="PostNL">PostNL</option>
                                                            <option value="PostNord">PostNord</option>
                                                            <option value="Purolator">Purolator</option>
                                                            <option value="Royal Mail">Royal Mail</option>
                                                            <option value="SF Express">SF Express</option>
                                                            <option value="SFC Fulfillment">SFC Fulfillment</option>
                                                            <option value="Sagawa (EN)">Sagawa (EN)</option>
                                                            <option value="Sagawa (JA)">Sagawa (JA)</option>
                                                            <option value="Sendle">Sendle</option>
                                                            <option value="Singapore Post">Singapore Post</option>
                                                            <option value="StarTrack">StarTrack</option>
                                                            <option value="TNT">TNT</option>
                                                            <option value="Toll IPEC">Toll IPEC</option>
                                                            <option value="UPS">UPS</option>
                                                            <option value="USPS">USPS</option>
                                                            <option value="Whistl">Whistl</option>
                                                            <option value="Yamato (EN)">Yamato (EN)</option>
                                                            <option value="Yamato (JA)">Yamato (JA)</option>
                                                            <option value="YunExpress">YunExpress</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="block-content block-content-full text-right">
                                                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check mr-1"></i>Change</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td class="" style="font-size: 12px !important;">
                                {{ $order->shipping_method }}
                            </td>
                            <td class="" style="font-size: 12px !important;">
                                {{ $order->ship_add }}
                            </td>
                            <td class="font-w600 text-center">
                                <button type="button" class="btn btn-sm btn-light push border-dark" style="border-radius: 100%" data-toggle="modal" data-target="#notesModal{{$order->id}}">
                                    <i class="si si-note "></i>
                                </button>

                                <div class="modal" id="notesModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Enter some notes {{ $order->name }}</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{ route('admin.store.order.notes', $order->id) }}" method="POST">
                                                    @csrf
                                                    <div class="block-content font-size-sm pb-2">
                                                        <textarea name="notes" class="form-control" id="" cols="25" rows="8" placeholder="Enter some notes"></textarea>
                                                    </div>
                                                    <div class="block-content block-content-full text-right">
                                                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check mr-1"></i>Add</button>
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
                </table>
                @else
                    <p>No Data!</p>
                @endif
                <div class="d-flex justify-content-end">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->
@endsection
