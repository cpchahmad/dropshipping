@extends('layouts.backend')
@section('content')
    <div class="content">
        <div class="row bulk-forms">
                <div class="fulfilment_process_form col-md-12">
                    <div class="block mb-0">
                            <div class="block-content">
                                <div class="d-flex justify-content-end">
{{--                                    <button type="button" class="btn btn-primary push mb-3" data-toggle="modal" data-target="#addTrackingModal">Add Bulk Tracking</button>--}}
{{--                                    <div class="modal" id="addTrackingModal" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">--}}
{{--                                        <div class="modal-dialog modal-md" role="document">--}}
{{--                                            <div class="modal-content">--}}
{{--                                                <div class="block block-themed block-transparent mb-0">--}}
{{--                                                    <div class="block-header bg-primary-dark">--}}
{{--                                                        <h3 class="block-title">ADD TRACKING FOR MULTIPLE ORDERS</h3>--}}
{{--                                                        <div class="block-options">--}}
{{--                                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">--}}
{{--                                                                <i class="fa fa-fw fa-times"></i>--}}
{{--                                                            </button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <form action="" method="POST">--}}
{{--                                                        @csrf--}}

{{--                                                        <div class="block-content font-size-sm pb-2 tracking">--}}
{{--                                                            <h5>Tracking Information</h5>--}}
{{--                                                            <input type="text" name="tracking_number" class="form-control mb-2" placeholder="Enter tracking number..">--}}
{{--                                                            <input type="text" name="tracking_url" class="form-control mb-2" placeholder="Enter tracking url..">--}}
{{--                                                            <select name="shipping_carrier" class="form-control status_select">--}}
{{--                                                                <option value="null" selected disabled>Select carrier</option>--}}
{{--                                                                <option value="4PX">4PX</option>--}}
{{--                                                                <option value="APC">APC</option>--}}
{{--                                                                <option value="Amazon Logistics UK">Amazon Logistics UK</option>--}}
{{--                                                                <option value="Amazon Logistics US">Amazon Logistics US</option>--}}
{{--                                                                <option value="Anjun Logistics">Anjun Logistics</option>--}}
{{--                                                                <option value="Australia Post">Australia Post</option>--}}
{{--                                                                <option value="Bluedart">Bluedart</option>--}}
{{--                                                                <option value="Canada Post">Canada Post</option>--}}
{{--                                                                <option value="Canpar">Canpar</option>--}}
{{--                                                                <option value="China Post">China Post</option>--}}
{{--                                                                <option value="Chukou1">Chukou1</option>--}}
{{--                                                                <option value="Correios">Correios</option>--}}
{{--                                                                <option value="Couriers Please">Couriers Please</option>--}}
{{--                                                                <option value="DHL Express">DHL Express</option>--}}
{{--                                                                <option value="DHL eCommerce">DHL eCommerce</option>--}}
{{--                                                                <option value="DHL eCommerce Asia">DHL eCommerce Asia</option>--}}
{{--                                                                <option value="DPD">DPD</option>--}}
{{--                                                                <option value="DPD Local">DPD Local</option>--}}
{{--                                                                <option value="DPD UK">DPD UK</option>--}}
{{--                                                                <option value="Delhivery">Delhivery</option>--}}
{{--                                                                <option value="Eagle">Eagle</option>--}}
{{--                                                                <option value="FSC">FSC</option>--}}
{{--                                                                <option value="Fastway Australia">Fastway Australia</option>--}}
{{--                                                                <option value="FedEx">FedEx</option>--}}
{{--                                                                <option value="GLS">GLS</option>--}}
{{--                                                                <option value="GLS (US)">GLS (US)</option>--}}
{{--                                                                <option value="Globegistics">Globegistics</option>--}}
{{--                                                                <option value="Japan Post (EN)">Japan Post (EN)</option>--}}
{{--                                                                <option value="Japan Post (JA)">Japan Post (JA)</option>--}}
{{--                                                                <option value="La Poste">La Poste</option>--}}
{{--                                                                <option value="New Zealand Post">New Zealand Post</option>--}}
{{--                                                                <option value="Newgistics">Newgistics</option>--}}
{{--                                                                <option value="PostNL">PostNL</option>--}}
{{--                                                                <option value="PostNord">PostNord</option>--}}
{{--                                                                <option value="Purolator">Purolator</option>--}}
{{--                                                                <option value="Royal Mail">Royal Mail</option>--}}
{{--                                                                <option value="SF Express">SF Express</option>--}}
{{--                                                                <option value="SFC Fulfillment">SFC Fulfillment</option>--}}
{{--                                                                <option value="Sagawa (EN)">Sagawa (EN)</option>--}}
{{--                                                                <option value="Sagawa (JA)">Sagawa (JA)</option>--}}
{{--                                                                <option value="Sendle">Sendle</option>--}}
{{--                                                                <option value="Singapore Post">Singapore Post</option>--}}
{{--                                                                <option value="StarTrack">StarTrack</option>--}}
{{--                                                                <option value="TNT">TNT</option>--}}
{{--                                                                <option value="Toll IPEC">Toll IPEC</option>--}}
{{--                                                                <option value="UPS">UPS</option>--}}
{{--                                                                <option value="USPS">USPS</option>--}}
{{--                                                                <option value="Whistl">Whistl</option>--}}
{{--                                                                <option value="Yamato (EN)">Yamato (EN)</option>--}}
{{--                                                                <option value="Yamato (JA)">Yamato (JA)</option>--}}
{{--                                                                <option value="YunExpress">YunExpress</option>--}}
{{--                                                                <option value="Other">Other</option>--}}
{{--                                                            </select>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="block-content block-content-full text-right">--}}
{{--                                                            <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>--}}
{{--                                                            <button type="button" class="btn btn-sm btn-primary tracking-btn"><i class="fa fa-check mr-1"></i>Add</button>--}}
{{--                                                        </div>--}}
{{--                                                    </form>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>

                                <form action="{{ route('admin.fulfill.orders') }}" method="POST">
                                    @csrf
                                    <table class="table table-bordered table-striped table-vcenter">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Order</th>
                                            <th class="">Products</th>
    {{--                                        <th class="text-center">Notify Customer</th>--}}
                                            <th class="text-center">Payment Method</th>
                                            @role('admin')
                                            <th class="text-center">Order Total</th>
                                            @endrole
{{--                                            <th class="text-center">Tracking</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody style="font-size: 15px !important;">
                                        @foreach($orders as $order)
{{--                                            @dd($order)--}}
                                            <input type="hidden" name="orders[]" value="{{ $order->wordpress_order_id }}">
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        {{ $order->number }}
                                                    </td>
                                                    <td style="width: 50%; font-size: 15px !important;">
                                                        @foreach($order->items as $item)
                                                            <span class="pb-2 d-block">-: x<strong>{{$item->quantity}}</strong> {{$item->title}} <strong>SKU: </strong>{{$item->sku}}</span>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if(isset($order->payment_method) && $order->payment_method != "")
                                                            {{ $order->payment_method }}
                                                        @else
                                                            Not Given!
                                                        @endif
                                                    </td>
                                                    @role('admin')
                                                        <td class="text-center align-middle">
                                                            {{$order->currency_symbol}}{{$order->total}}
                                                        </td>
                                                    @endrole
{{--                                                    <td class="align-middle text-center tracking-td">--}}

{{--                                                    </td>--}}
                                                </tr>

                                        </tbody>
                                        @endforeach
                                    </table>
                                    <button type="submit" class="btn bulk_fulfill_items_btn btn-block btn-primary mb-3 btn-block"> Fulfill Items</button>
                                </form>
                            </div>
                        </div>

                </div>

        </div>
    </div>
@endsection

@section('js_after')
    <script>
        $(document).ready(function () {
            $('.tracking-btn').click(function () {
                var tracking_number = $("input[name=tracking_number]").val();
                var tracking_url = $("input[name=tracking_url]").val();
                var shipping_carrier = $("select[name=shipping_carrier]").find(":selected").text();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: `/admin/add/order/tracking`,
                    type: 'POST',
                    data: { tracking_number : tracking_number, tracking_url : tracking_url, shipping_carrier : shipping_carrier},
                    success: function(res) {
                        var response = res.data;
                        if(response == 'success') {
                            toastr.success("Bulk Tracking Added Successfully!") ;
                            $('#addTrackingModal').modal('hide');
                            $('.tracking-td').empty();
                            $('.tracking-td').append(
                               `
                                <span>
                                    <strong>Tracking number:</strong> ${res.tracking.tracking_number}<br>
                                    <strong>Tracking URL:</strong> ${res.tracking.tracking_url}<br>
                                    <strong>Shipping carrier:</strong> ${res.tracking.tracking_company}<br>
                                    <input type="hidden" value="${res.tracking.tracking_number}" name="tracking_number">
                                    <input type="hidden" value="${res.tracking.tracking_url}" name="tracking_url">
                                    <input type="hidden" value="${res.tracking.tracking_company}" name="tracking_company">
                                </span>
                               `
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection

