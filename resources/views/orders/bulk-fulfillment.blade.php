@extends('layouts.backend')
@section('content')
    <div class="content">
        <div class="row bulk-forms">
                <div class="fulfilment_process_form col-md-12">
                    <div class="block mb-0">
                            <div class="block-content">
                                <p class="atleast-one-item alert alert-warning" style="display: none"> <i class="fa fa-exclamation-circle"></i> You need to fulfill at least 1 item.</p>
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
                                        <th class="text-center">Add Tracking</th>

                                    </tr>
                                    </thead>
                                    <tbody style="font-size: 15px !important;">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="text-center align-middle">
                                                {{ $order->name }}
                                            </td>
                                            <td style="width: 50%; font-size: 15px !important;">
                                                @foreach($order->line_items()->get() as $item)
                                                    <span class="pb-2 d-block">-: x<strong>{{$item->quantity}}</strong> {{$item->title}} <strong>SKU: </strong>{{$item->sku}}</span>

{{--                                                    @if($item->vendor_chk)--}}
{{--                                                        <span class="d-block font-weight-bolder">Vendors: </span>--}}
{{--                                                        <input type="hidden" value="{{ $item->id }}" name="line[]">--}}
{{--                                                        {{ $item->vendors }}--}}
{{--                                                    @endif--}}
                                                @endforeach
                                            </td>
{{--                                            <td style="width: 10%" class="text-center align-middle">--}}
{{--                                                <input type="checkbox" name="notify[]" value="yes">--}}
{{--                                            </td>--}}
                                            <td class="text-center align-middle">
                                                {{ $order->processing_method }}
                                            </td>
                                            @role('admin')
                                                <td class="text-center align-middle">
                                                    ${{$order->total_price}}
                                                </td>
                                            @endrole
                                            <td class="align-middle text-center">
                                                <button type="button" class="btn btn-sm btn-light push mb-0" data-toggle="modal" data-target="#updateModal{{$order->id}}">Add Tracking</button>

                                                <div class="modal" id="updateModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-small" aria-hidden="true">
                                                    <div class="modal-dialog modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="block block-themed block-transparent mb-0">
                                                                <div class="block-header bg-primary-dark">
                                                                    <h3 class="block-title">ADD TRACKING FOR {{ $order->name }}</h3>
                                                                    <div class="block-options">
                                                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                            <i class="fa fa-fw fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <form action="{{ route('admin.add.tracking', $order->id) }}" method="POST">
                                                                    @csrf

                                                                    <div class="block-content font-size-sm pb-2 tracking">
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
                                        </tr>
                                    </tbody>
                                    @endforeach

                                </table>

                            </div>
                        </div>
                    <form action="{{ route('admin.fulfill.orders') }}" method="POST">
                        @csrf
                        @foreach($orders as $order)
                            <input type="hidden" name="orders[]" value="{{ $order->id }}">
                        @endforeach
                        <button type="submit" class="btn bulk_fulfill_items_btn btn-block btn-primary mb-3 btn-block"> Fulfill Items</button>
                    </form>

                </div>

        </div>
    </div>


@endsection
