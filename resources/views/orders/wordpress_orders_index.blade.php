@extends('layouts.backend')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" type="text/css" media="screen" />
    <style type='text/css'>
        .li-content{
           display: none !important;
        }
    </style>
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>


    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>

    <script>
        $(document).ready(function () {
            $(".fancybox").fancybox();

            $(".status_select").change(function(){
                var id = $(this).find("option:selected").val();

                switch (id){
                    case "Fulfilled":
                        $('.tracking').show();
                        break;
                }
            });

            $('.item-checkbox').change(function() {
                var li = $(this).parent().parent();
                var id = $(this).attr('id');
                li.attr('style', 'display: none !important');
                var modal = $(`#printModal${id}`);
                modal.find('.partial-msg').show();
            });

            $(".qty").change(function(){
                var value = $(this).val();
                var max = $(this).attr('max');
                var id = $(this).data('item');
                var modal = $(`#printModal${id}`);
                if(value < max) {
                    modal.find('.partial-msg').show();
                }
                else if(value == max){
                    modal.find('.partial-msg').hide();
                }
                else {
                    modal.find('.partial-msg').show();
                }
            });


            $(".vendors").change(function(){
                $(this).parent().parent().parent().parent().find('.product_price').val($(this).attr('data-price'));
                console.log(234);
                // $('.product_price').val($(this).attr('data-price'));
            });

            $('.show-images-btn').click(function() {
                var id  = $(this).attr('id');
                var modal = $(`#imagesModal${id}`);
                modal.css({ "display": "block",
                            "width": "100%",
                            "height": "100vh",
                            "background": "rgba(0,0,0,0.3)"});
                modal.show();
                $.ajax({
                    url: `/admin/wordpress/show/line/images/${id}`,
                    type: 'GET',
                    success: function(res) {

                        modal.find('.row').empty();
                        modal.find('.row').append(res);
                    }
                });

            });

            $('.modal-close-btn').click(function () {
                var id  = $(this).attr('id');
                var modal = $(`#imagesModal${id}`);
                modal.hide();
            })
        });

        $('.check-order-all').change(function () {
            unset_bulk_array()


            if($(this).is(':checked')){
                $('.bulk-div').show();
                $(this).parent().parent().parent().parent().next().find('input.check-order').prop('checked',true);
            }
            else{
                $('.bulk-div').hide();
                $(this).parent().parent().parent().parent().next().find('input.check-order').prop('checked',false);

            }

            set_bulk_array();

        });
        $('.check-order').change(function () {
            if($(this).is(':checked')){
                $('.bulk-div').show();
                unset_bulk_array();
                set_bulk_array();
            }
            else{
                unset_bulk_array();
                set_bulk_array();
                if($('.check-order:checked').length === 0){
                    $('.bulk-div').hide();
                }

            }

        });
        function set_bulk_array() {
            var values = [];
            $('.check-order:checked').each(function () {
                values.push($(this).val());
            });
            $('#bulk-fullfillment').find('input:hidden[name=orders]').val(values);

        }
        function unset_bulk_array() {
            $('#bulk-fullfillment').find('input:hidden[name=orders]').val('');

        }
        $('.bulk-fulfill-btn').click(function () {
            $('#bulk-fullfillment').submit();
        });

        $(".filter-btn").click(function(){
            $(".filters").slideToggle();
        });

        $('.print-btn').click(function () {
            var id = $(this).attr('id');
            var checkbox = $(`.print${id}`).find('.item-checkbox');
            checkbox.hide();


            var printContents = $(`.print${id}`).html();


            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            window.location.reload();
            // document.body.innerHTML = originalContents;




        });

        $('.add-notes-btn').click(function () {
            var id = $(this).attr('id');
            var notes = $(`textarea[name=notes${id}]`).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: `/admin/store/order/notes/${id}`,
                data: { notes : notes},
                type: 'POST',
                success: function(res) {
                    var response = res.data;
                    if(response == 'success') {
                        toastr.success("Notes added Successfully!") ;
                         console.log("hi", id);
                         console.log("df", $('td#'+id));
                        $('td#'+id).css("background-color", "yellow");
                        console.log("sdf", id);
                        $('td#'+id).find('.notes-div').append(`
                              <li style="font-size: 12px !important; z-index: 99999 !important; color: #575757;">${ res.note.notes }</li>
                        `);

                        $('#notesModal'+id).modal('hide');
                    }

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

        <form class="js-form-icon-search push mb-0" action="" method="get">
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

        @role('admin')
        <div class="text-right">
            <a class="btn btn-primary" href="{{ route('admin.sync.order', session()->get('current_shop_domain')) }}"> <i class="fa fa-sync"></i> Sync Orders </a>
        </div>
        @endrole

        <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-content block-content-full">
                <div class="block-header bulk-div px-0 justify-content-end" style="display: none">
                    <button class="btn btn-outline-primary btn-sm btn-lg bulk-fulfill-btn">Bulk Fulfillment</button>
                </div>
                @if(count($orders)> 0)
                    <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox d-inline-block">
                                    <input type="checkbox" class="custom-control-input check-order-all" id="check-all" name="check-all">
                                    <label class="custom-control-label" for="check-all"></label>
                                </div>
                            </th>
                            <th class="text-center" style="width: 80px;">Order</th>
                            <th class="text-center">Products</th>
                            <th class="text-center" style="width: 150px;">Tracking & Shipping</th>
                            <th class="text-center" style="width: 120px;">Shipping Method</th>
                            <th class="text-center" style="width: 150px;">Shipping Address</th>
                            <th class="text-center" style="width: 220px;">Notes</th>
                            <th class="text-center" style="width: 120px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $index =>$order)
                        <tr style="background: {{ $order->bg }}; color: {{ $order->color }}">
{{--                            @dd($order)--}}
                            <td>
                                @if($order->is_unfulfilled)
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" class="custom-control-input check-order" id="row_{{$index}}" name="check_order[]" value="{{$order->wordpress_order_id}}">
                                        <label class="custom-control-label" for="row_{{$index}}"></label>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center" style="font-size: 12px !important;">
                                <a class="d-block font-weight-bold" style="font-size: 14px !important;">#{{ $order->number }}</a>
                                {{Carbon\Carbon::parse($order->date_completed)->format('M H, Y')}}
                            </td>
                            <td class="" style="font-size: 12px !important;">
                                <span class="text-left font-w400 text-uppercase badge badge-dark">{{ $order->fulfillment }}</span>
                                @role('admin')
                                    <span class="text-left font-weight-bold text-uppercase ml-4" style="font-size: 15px;">{{$order->currency_symbol}}{{ $order->total }}</span>
                                @endrole
                                    @php
                                        $counter = 0;
                                    @endphp
{{--                                @dd(json_decode($order->line_items))--}}
                                    @foreach(json_decode($order->line_items) as $item)
                                        @if($counter == count(json_decode($order->line_items)) - 1)
{{--                                            @dd($item)--}}
                                            <form class='row d-flex align-items-center py-2' action="{{ route('admin.store.order.vendor') }}" method="POST">
                                            @csrf
                                            <div class="col-3">
{{--                                                @dd($item)--}}
                                                <a class="fancybox" rel="group" @if(isset($item->image)) href='{{ $item->image }}' @endif>
                                                    <img @if(isset($item->image)) src="{{ $item->image }}" @else src = "{{ asset('\random_product.jpg')}}"  @endif  alt='No img' class="img-fluid hover-img" style="width: 100%; height: auto; z-index: 9999;">
                                                </a>
                                            </div>
                                            <div class="col-7">
                                                <span class="d-block font-weight-lighter">{{$item->name}}     @if(!(is_null($item->sku)) && $item->sku != '')<span class=" font-weight-lighter"><span class="font-weight-bold"> </span>@endif</span>
                                                @if(isset($item->shopify_variant->title) && $item->shopify_variant->title !== "Default Title")<span class="d-block font-weight-bold">{{$item->shopify_variant->title}}</span>@endif
                                                @if(!(is_null($item->sku)) && $item->sku != '')<span class="d-block font-weight-lighter"><span class="font-weight-bold">SKU: </span> {{$item->sku}}</span>@endif
{{--                                                @if(!(is_null($item->fulfillment_response)))<span class="badge badge-primary font-weight-bold" style="font-size: 12px; !important;">This Line is fulfilled in: {{$item->fulfillment_response}}</span>@endif--}}
{{--                                                <span> {{ $item->prop }}</span>--}}
{{--@dd(json_decode(\App\WordpressProduct::first()->images))--}}
{{--                                                @dd($item)--}}
                                                <a type="button" class="btn-link mt-2 show-images-btn" style=" text-decoration: underline;" id="{{ $item->product_id }}" >
                                                    View all images
                                                </a>

                                                <div class="modal" id="imagesModal{{$item->product_id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                                    <div class="modal-dialog modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="block block-themed block-transparent mb-0">
                                                                <div class="block-header bg-primary-dark">
                                                                    <h3 class="block-title">Images</h3>
                                                                    <div class="block-options">
                                                                        <button type="button" class="btn-block-option modal-close-btn" id="{{ $item->product_id }}" aria-label="Close">
                                                                            <i class="fa fa-fw fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="block-content font-size-sm pb-5">
                                                                    <div class="row">
                                                                        <p class="pl-2">Loading..</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

{{--                                                --}}

{{--                                                    <?php--}}
{{--                                                    //                                                    dd($item->id);--}}
{{--                                                    $product_vendor_detail = \App\ProductVendorDetail::where('shop_id', session()->get('current_shop_domain'))->where('shopify_product_id', $item->product_id)->get();--}}
{{--                                                    //                                                    dd($product);--}}
{{--                                                    ?>--}}
{{--                                                    @if(isset($product_vendor_detail) && !($order->ful_check))--}}
{{--                                                        --}}{{--                                                    @dd($product->id)--}}
{{--                                                        <input type="hidden" value="{{ $item->id }}" name="line[]">--}}
{{--                                                        <span class="d-block font-weight-bolder">Vendors: </span>--}}

{{--                                                        @foreach ($product_vendor_detail as $details)--}}
{{--                                                            <li class='mb-2 ml-3 list-unstyled font-weight-bold'>--}}
{{--                                                            <div class='row d-flex'>--}}
{{--                                                                <div class='mr-2'>--}}
{{--                                                                    {{ $details->name }}--}}
{{--                                                                </div>--}}
{{--                                                                <div class='font-weight-bold mr-2'>--}}
{{--                                                                    <span class=>${{ number_format($details->cost, 2) }}</span>--}}
{{--                                                                </div>--}}
{{--                                                                <div class='font-weight-bold'>--}}
{{--                                                                    <a href='{{ $details->url }}' target='_blank'>Place Order</a>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </li>--}}
{{--                                                        @endforeach--}}
{{--                                                    @endif--}}
{{--                                                --}}
                    {{--Working area--}}
{{--                                            @if($order->ful_check && $item->vendor_chk)--}}
                                                    <?php
                                                    //                                                    dd($item->id);
                                                    $product_vendor_detail = \App\ProductVendorDetail::where('shop_id', session()->get('current_shop_domain'))->where('shopify_product_id', $item->product_id)->get();
                                                    //                                                    dd($product);
                                                    ?>
                                                    <input type="hidden" value="{{ $item->id }}" name="line[]">
                                                    <span class="d-block font-weight-bolder">Vendors: </span>

                                                    @foreach ($product_vendor_detail as $details)
                                                        <li class='mb-2 ml-3 list-unstyled font-weight-bold'>
                                                            <div class='row d-flex'>
                                                                <div class='mr-2'>
                                                                    {{ $details->name }}
                                                                </div>
                                                                <div class='font-weight-bold mr-2'>
                                                                    <span class=>${{ number_format($details->cost, 2) }}</span>
                                                                </div>
                                                                <div class='font-weight-bold'>
                                                                    <a href='{{ $details->url }}' target='_blank'>Place Order</a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
{{--                                                @endif--}}
                                                @if($order->unful_check && $item->order_vendor()->count() > 0)

                                                    <span class="d-block font-weight-bolder mt-1">Added Vendors: </span>
                                                    @foreach ($item->shopify_variant->shopify_product->product_vendor_details as $details)
                                                        @php
                                                            if(\App\OrderVendor::where('vendor_id', $details->id)->where('line_id', $item->id)->exists()) {
                                                                $checked = 'checked';
                                                                $order_vendor = \App\OrderVendor::where('vendor_id', $details->id)->where('line_id', $item->id)->first();
                                                                $special_price = $order_vendor->product_price;
                                                                $flag = true;
                                                            }
                                                            else {
                                                                $checked = '';
                                                                $flag = false;
                                                            }
                                                        @endphp
                                                        @if($flag)
                                                            <li class='mb-2 ml-3 list-unstyled font-weight-bold'>
                                                                <div class='row d-flex'>
                                                                    <div class='font-weight-bold'>
                                                                        <span class="d-block">{{ $details->name }}</span>
                                                                        <span class="d-block">Vendor Price: ${{ number_format($details->cost, 2) }}</span>
                                                                        @if($details->cost !== $special_price)
                                                                            <span class=" d-block"><span class=" badge badge-primary font-weight-bold" style="font-size: 12px; !important;">Special Price</span> ${{ number_format($special_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif

                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="text-right col-2">
                                                <p class="font-weight-bold">x{{$item->quantity}}</p>
                                            </div>
                                        </form>
                                        @else
                                            <form class='row d-flex align-items-center py-2 border-bottom' action="{{ route('admin.store.order.vendor') }}" method="POST">
                                            @csrf
                                            <div class="col-3">
                                                <a class="fancybox" rel="group" href='{{ $item->image }}' >
                                                    <img src="{{ $item->image }}" alt='No img' class="img-fluid hover-img" style="width: 100%; height: auto;">
                                                </a>
                                            </div>
                                            <div class="col-7">
                                                <span class="d-block font-weight-lighter">{{$item->name}}     @if(!(is_null($item->sku)) && $item->sku != '')<span class=" font-weight-lighter"><span class="font-weight-bold"> [SKU: </span> {{$item->sku}}]</span>@endif</span>
                                                @if(isset($item->shopify_variant->title) && $item->shopify_variant->title !== "Default Title")<span class="d-block font-weight-bold">{{$item->shopify_variant->title}}</span>@endif
{{--                                                <span> {{ $item->prop }}</span>--}}

                                                <a type="button" class="btn-link mt-2 show-images-btn" style=" text-decoration: underline;" id="{{ $item->product_id }}" >
                                                    View all images
                                                </a>

                                                <div class="modal" id="imagesModal{{$item->product_id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                                    <div class="modal-dialog modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="block block-themed block-transparent mb-0">
                                                                <div class="block-header bg-primary-dark">
                                                                    <h3 class="block-title">Images</h3>
                                                                    <div class="block-options">
                                                                        <button type="button" class="btn-block-option modal-close-btn" id="{{ $item->product_id }}" aria-label="Close">
                                                                            <i class="fa fa-fw fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="block-content font-size-sm pb-5">
                                                                    <div class="row">
                                                                        <p class="pl-2">Loading..</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
{{--                                            @if(!(is_null($item->fulfillment_response)))<span class="badge badge-primary font-weight-bold" style="font-size: 12px; !important;">This Line is fulfilled in: {{$item->fulfillment_response}}</span>@endif--}}
                                                <?php
//                                                    dd($item->id);
                                                $product_vendor_detail = \App\ProductVendorDetail::where('shop_id', session()->get('current_shop_domain'))->where('shopify_product_id', $item->product_id)->get();
//                                                    dd($product);
                                                ?>
                                                @if(isset($product_vendor_detail) && !($order->ful_check))
{{--                                                    @dd($product->id)--}}
                                                    <input type="hidden" value="{{ $item->id }}" name="line[]">
                                                    <span class="d-block font-weight-bolder">Vendors: </span>

                                                    @foreach ($product_vendor_detail as $details)
                                                        <li class='mb-2 ml-3 list-unstyled font-weight-bold'>
                                                            <div class='row d-flex'>
                                                                <div class='mr-2'>
                                                                    {{ $details->name }}
                                                                </div>
                                                                <div class='font-weight-bold mr-2'>
                                                                    <span class=>${{ number_format($details->cost, 2) }}</span>
                                                                </div>
                                                                <div class='font-weight-bold'>
                                                                    <a href='{{ $details->url }}' target='_blank'>Place Order</a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif

                                                @if($order->unful_check && $item->order_vendor()->count() > 0)

                                                    <span class="d-block font-weight-bolder mt-1">Added Vendors: </span>
                                                    @foreach ($item->shopify_variant->shopify_product->product_vendor_details as $details)
                                                        @php
                                                            if(\App\OrderVendor::where('vendor_id', $details->id)->where('line_id', $item->id)->exists()) {
                                                                $checked = 'checked';
                                                                $order_vendor = \App\OrderVendor::where('vendor_id', $details->id)->where('line_id', $item->id)->first();
                                                                $special_price = $order_vendor->product_price;
                                                                $flag = true;
                                                            }
                                                            else {
                                                                $checked = '';
                                                                $flag = false;
                                                            }
                                                        @endphp
                                                        @if($flag)
                                                            <li class='mb-2 ml-3 list-unstyled font-weight-bold'>
                                                                <div class='row d-flex'>
                                                                    <div class='font-weight-bold'>
                                                                        <span class="d-block">{{ $details->name }}</span>
                                                                        <span class="d-block">Vendor Price: ${{ number_format($details->cost, 2) }}</span>
                                                                        @if($details->cost !== $special_price)
                                                                            <span class="d-block"><span class=" badge badge-primary font-weight-bold" style="font-size: 12px; !important;">Special Price</span> ${{ number_format($special_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif

                                                    @endforeach
                                                @endif

                                            </div>
                                            <div class="text-right col-2">
                                                <p class="font-weight-bold">x{{$item->quantity}}</p>
                                            </div>
                                        </form>
                                        @endif

                                        @php
                                            $counter++;
                                        @endphp
                                    @endforeach

                            </td>
                            <td class="text-left align-middle" style="font-size: 13px !important;">
{{--                                @if($order->order_fulfillments()->count() > 0)--}}

                                   <ul class="pl-3 list-unstyled">
                                       @php
                                           $counter = 0;
                                       @endphp
{{--                                       @foreach($order->order_fulfillments as $fulfillment)--}}
{{--                                           @if($counter == count( $order->items ) - 1)--}}
{{--                                               <li class="pl-0 pb-2 border-bottom my-2"> <span class="d-inline-block badge badge-primary">{{ $fulfillment->fulfillment_response }}</span>--}}
{{--                                                   <span class="d-block">${{ number_format($fulfillment->shipping_price_usd,2) }} @if(!(is_null($fulfillment->shipping_price_rmb))) {{ '(RMB '.number_format($fulfillment->shipping_price_rmb,2).')' }} @endif</span>--}}
{{--                                                   {{ $fulfillment->tracking_number }} <br>--}}
{{--                                                   <a href="{{ $fulfillment->tracking_url }}" class="text-white">{{ $fulfillment->tracking_url }}</a> <br>--}}
{{--                                                   {{ $fulfillment->tracking_company }} <br>--}}
{{--                                               </li>--}}
{{--                                           @else--}}
{{--                                               <li class="pl-0 "> <span class="d-inline-block badge badge-primary">{{ $fulfillment->fulfillment_response }}</span>--}}
{{--                                                   <span class="d-block">${{ number_format($fulfillment->shipping_price_usd,2) }} @if(!(is_null($fulfillment->shipping_price_rmb))) {{ '(RMB '.number_format($fulfillment->shipping_price_rmb,2).')' }} @endif</span>--}}
{{--                                                   {{ $fulfillment->tracking_number }} <br>--}}
{{--                                                   <a href="{{ $fulfillment->tracking_url }}" class="text-white">{{ $fulfillment->tracking_url }}</a> <br>--}}
{{--                                                   {{ $fulfillment->tracking_company }} <br>--}}
{{--                                               </li>--}}
{{--                                           @endif--}}


{{--                                       @php--}}
{{--                                           $counter++;--}}
{{--                                       @endphp--}}
{{--                                       @endforeach--}}
                                   </ul>
{{--                                @else--}}
                                    Not Added Yet!
{{--                                @endif--}}
                            </td>

                            <td class="align-middle text-center" style="font-size: 12px !important;">
                                @foreach(json_decode($order->shipping_lines) as $ship_method)
                                    {{ $ship_method->method_title }}
                                @endforeach
                            </td>
                            <td class="align-middle" style="font-size: 12px !important;">
                                @foreach(json_decode($order->shipping) as $ship_add)
                                    {{ $ship_add }}
                                @endforeach
                            </td>
                            <td  class="font-w600 text-center align-middle change-td-color" @if($order->note_check != false) style="background: yellow;"  @endif id="{{ $order->id }}">
                                <button type="button" class="btn btn-sm btn-light push border-dark" style="border-radius: 100%" data-toggle="modal" data-target="#notesModal{{$order->id}}">
                                    <i class="si si-note "></i>
                                </button>
                                <div class="text-left notes-div">
                                    @if(!(is_null($order->customer_note)) && $order->customer_note !== '')
                                        <li style="font-size: 12px !important; color: #575757;">{{ $order->customer_note }}</li>
                                    @endif
                                    @if(count($order->shopify_order_notes)>0)
                                        @foreach($order->shopify_order_notes as $note)
                                            <li style="font-size: 12px !important; color: #575757;">{{ $note->notes }}</li>
                                        @endforeach
                                    @endif
                                </div>

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
                                                <form action="{{ route('admin.store.order.notes', $order->wordpress_order_id) }}" method="POST">
                                                    @csrf
                                                    <div class="block-content font-size-sm pb-2">
                                                        <textarea name="notes{{$order->id}}" class="form-control noteTextarea" id="" cols="25" rows="8" placeholder="Enter some notes"></textarea>
                                                    </div>
                                                    <div class="block-content block-content-full text-right">
                                                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-sm btn-primary add-notes-btn" id="{{ $order->id }}"><i class="fa fa-check mr-1"></i>Add</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
{{--                            @dd($order)--}}
                            <td class="align-middle" style="font-size: 12px !important;">
                               <div class="">
                                   <button type="button" class="btn btn-sm btn-success push w-100" data-toggle="modal" data-target="#printModal{{$order->id}}">Print</button>

                                   @if($order->status_check)
{{--                                       <form action="{{ route('admin.change.order.status', $order->wordpress_order_id) }}" method="POST">--}}
{{--                                           @csrf--}}
{{--                                           <button type="submit" class="btn btn-sm btn-primary push" >Mark as Fulfilled</button>--}}
                                           <button type="button" class="btn btn-sm btn-primary push" data-toggle="modal" data-target="#updateModal{{$order->wordpress_order_id}}">Mark as Fulfilled</button>
{{--                                       </form>--}}
                                   @endif
                               </div>

                                <div class="modal" id="printModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Packing Slip #{{ $order->number }}</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{ route('admin.print.order.shipping', $order->id) }}" method="POST">
                                                    @csrf

                                                    <div class="block-content font-size-sm pb-2 print{{ $order->id }}">
                                                        <div class="d-flex justify-content-between align-middle">
                                                            <h3>Packing Slip</h3>
                                                            <h5>
                                                                Order #{{ $order->number }}
                                                                <br>
                                                                {{Carbon\Carbon::parse($order->date_completed)->format('M H, Y')}}
                                                            </h5>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5 for="">SHIP TO</h5>
                                                                <h5>
                                                                    {{ $order->ship_add }}
                                                                </h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 for="">BILL TO</h5>
                                                                <h5>
                                                                    {{ $order->bill_address }}
                                                                </h5>
                                                            </div>

                                                        </div>

                                                        <hr>
                                                        <div class="d-flex justify-content-between align-middle">
                                                            <h3>Items</h3>
                                                            <h5>Quantity</h5>
                                                        </div>
                                                        <ul class="list-unstyled">
{{--                                                            @dd($order->line_items)--}}
                                                            @foreach(json_decode($order->line_items) as $item)
                                                                    <li class='row d-flex align-items-center py-2 border-bottom item-li' action="{{ route('admin.store.order.vendor') }}" method="POST">
                                                                        @csrf
                                                                        <div class="col-2 align-middle d-flex justify-content-between">
{{--                                                                            <input type="hidden" name="item_id[]" value="{{ $item->id }}">--}}
                                                                            <input type="checkbox" class="form-control-sm my-auto ml-2 item-checkbox" checked name="item_id{{ $order->id }}[]" id="{{$order->id}}" value="{{ $item->id }}">
                                                                            <img @if(isset($item->image)) src="{{ $item->image }}" @else src = "{{ asset('\random_product.jpg')}}"  @endif  alt='No img' class="img-fluid" style="width: 100px; height: auto; opacity: 1;">
                                                                        </div>
                                                                        <div class='col-7'>
                                                                            <h5 class="d-block font-weight-bold mb-2">{{$item->name}}     @if(!(is_null($item->sku)) && $item->sku != '')<h5 class="font-weight-bold mb-1"> [SKU: {{$item->sku}}]</h5>@endif</h5>
                                                                            @if(isset($item->shopify_variant->title) && $item->shopify_variant->title !== "Default Title")<h5 class="d-block font-weight-bold mb-2">{{$item->shopify_variant->title}}</h5>@endif
{{--                                                                            <h5>{{ $item->prop }}</h5>--}}
                                                                        </div>
                                                                        <div class="text-right col-3">
                                                                            <div class="form-group">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control d-inline qty" id="{{ $item->id }}" data-item="{{ $order->id }}" name="item_fulfill_quantity{{ $order->id }}[]" min="1" @if($item->quantity != 0) max="{{ $item->quantity }}" @endif() value="{{ $item->quantity }}">
                                                                                    <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    of {{ $item->quantity }}
                                                                                </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <li/>
                                                            @endforeach
                                                        </ul>

                                                        <div class="row text-center">
                                                            <div class="col-md-12">
                                                                <h5>Thanks for Shipping with us!</h5>
                                                                <h5>Contact us if you have any questions or concerns regarding the items</h5>

                                                                <h5 class="text-danger partial-msg" style="display: none; ">This is not your full order and some items might be pending or will come in next shipment. Please contact us for more info</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="block-content block-content-full text-right">
                                                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
{{--                                                        @dd($order->id)--}}
                                                        <button type="button" class="btn btn-sm btn-primary print-btn" id="{{ $order->id }}"><i class="fa fa-check mr-1"></i>Print</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal" id="updateModal{{$order->wordpress_order_id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
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
                                                <form action="{{ route('admin.change.order.status', $order->wordpress_order_id) }}" method="POST">
                                                    @csrf

                                                    <div class="block-content font-size-sm pb-2 tracking " style="color: black;">
                                                        <h5>Quantity to fulfill</h5>
                                                        <ul class="list-unstyled">
{{--                                                            @dd($order)--}}
                                                            @foreach(json_decode($order->line_items) as $item)
{{--                                                                    @dd($item)--}}
                                                                @if(!isset($item->status))
{{--                                                                    @dd('true')--}}
                                                                    <li class='row d-flex align-items-center py-2 border-bottom ' action="{{ route('admin.store.order.vendor') }}" method="POST">
                                                                    @csrf
                                                                    <div class="col-2">
                                                                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                                                        <img @if(isset($item->image)) src="{{ $item->image }}" @else src = "{{ asset('\random_product.jpg')}}"  @endif  alt='No img' class="img-fluid" style="width: 100px; height: auto;">
                                                                    </div>
                                                                    <div class='col-7'>
                                                                        <span class="d-block font-weight-lighter">{{$item->name}}</span>
                                                                        <span class="d-block font-weight-lighter"><span class='font-weight-bold'>SKU: </span> {{$item->sku}}</span>
{{--                                                                        @dd($order->vendor_chk)--}}
{{--                                                                        @dd($order->ful_check)--}}
                                                                        @if($order->ful_status )
{{--                                                                            @dd('ok')--}}
{{--                                                                            <span class="d-block font-weight-bolder">Vendors: </span>--}}
{{--                                                                           Quantity to fulfill     @dd("ok")--}}

                                                                            <?php
                                                                            //                                                    dd($item->id);
                                                                                $product_vendor_detail = \App\ProductVendorDetail::where('shop_id', session()->get('current_shop_domain'))->where('shopify_product_id', $item->product_id)->get();
                                                                            //                                                    dd($product);
                                                                            ?>
                                                                            @if(isset($product_vendor_detail) && !($order->ful_check))
                                                                                {{--                                                    @dd($product->id)--}}
                                                                                <input type="hidden" value="{{ $item->id }}" name="line[]">
                                                                                <span class="d-block font-weight-bolder">Vendors: </span>

{{--                                                                                <input type="hidden" value="{{ $item->id }}" name="line[]">--}}
                                                                                @foreach ($product_vendor_detail as $details)

                                                                                    <div class='row d-flex ml-0 mb-1'>
                                                                                            @php
                                                                                                if(\App\OrderVendor::where('shop_id', session()->get('current_shop_domain'))->where('vendor_id', $details->id)->where('line_id', $item->id)->exists()) {
                                                                                                    $checked = 'checked';
                                                                                                }
                                                                                                else {
                                                                                                    $checked = '';
                                                                                                }
                                                                                            @endphp
                                                                                            <div class='mr-2'>
{{--                                                                                                @dd($item->id)--}}
                                                                                                <input type='radio' data-price="{{ $details->cost }}"class='from-control vendors' name='item_vendor_{{$item->id}}' value='{{ $details->id }}'
                                                                                                       {{ $checked}}>
                                                                                            </div>
                                                                                            <div class='mr-2'>
                                                                                                {{ $details->name }}
                                                                                            </div>
                                                                                            <div class='font-weight-bold mr-2'>
                                                                                                <span class=>${{ number_format($details->cost, 2) }}</span>
                                                                                            </div>
                                                                                            <div class='font-weight-bold'>
                                                                                                <a href='{{ $details->url }}' target='_blank'>Place Order</a>
                                                                                            </div>
                                                                                        </div>
                                                                                @endforeach
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-right col-3">
{{--                                                                        @dd($item)--}}
                                                                        @if(!($order->ful_check)  )
                                                                            <div class="form-group">

                                                                            <div class="input-group">
                                                                                <input type="text" name="product_price_{{$item->id}}" value="0.00" class="d-inline form-control product_price">
                                                                                <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    $
                                                                                </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        <div class="form-group">
                                                                            <div class="input-group">
                                                                                <input type="number"  class="form-control d-inline "  name="item_fulfill_quantity[]" min="1" max="{{ $item->quantity }}" value="{{ $item->quantity }}">
                                                                                <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    of {{ $item->quantity }}
                                                                                </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <li/>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <h5>Tracking Information</h5>
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
                                                            <div class="col-md-4 ">
                                                                <h5 class="mb-0">Shipping Cost Information</h5>
                                                                <div class="block-content font-size-sm pl-0">
                                                                    <input type="text" name="shipping_price" class="form-control" placeholder="Enter Shipping price..">
                                                                    <select name="shipping_currency"  class="form-control mt-2">
                                                                        <option value="usd">USD</option>
                                                                        <option value="rmb">RMB</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
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

                                <div class="modal" id="priceModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Enter Shipping Price for {{ $order->name }}</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{ route('admin.store.order.shipping.price', $order->id) }}" method="POST">
                                                    @csrf
                                                    <div class="block-content font-size-sm pb-2">
                                                        <input type="text" name="shipping_price" class="form-control" placeholder="Enter Shipping price..">
                                                        <select name="shipping_currency" id="" class="form-control mt-3">
                                                            <option value="" selected disabled>-- Select currency --</option>
                                                            <option value="usd">USD</option>
                                                            <option value="rmb">RMB</option>
                                                        </select>
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
                    {{ $orders->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->

    <form action="{{route('app.orders.bulk.fulfillment')}}" id="bulk-fullfillment" method="post">
        @csrf
        <input type="hidden" name="orders" class="">
    </form>
@endsection
