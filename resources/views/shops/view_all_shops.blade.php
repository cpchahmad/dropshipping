@extends('layouts.backend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ One.helpers(['select2']); });</script>




@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Shops
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('shops.index') }}">View All Shops</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <div class="row mt-1 d-flex justify-content-end mr-1">
            <a class="btn btn-primary text-white" href="{{route('shops.create')}}">Add Shop</a>
        </div>

        <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-header">
                <h3 class="block-title">Shops</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
{{--                @if(count($users) > 0)--}}
                    <table class="table table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th style="width: 30%;">Shops</th>
                            <th style="width: 10%;">Shop Type</th>
                            <th style="width: 15%;">Products Count</th>
                            <th style="width: 15%;">Orders Count</th>
                            <th style="width: 20%;">Sync Product/Order</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        registered shops, with products count, orders count, sync Products/Orders Buttons, Edit and Delete Button--}}
                        @foreach($shop_data as $key=>$shop)
{{--                            @dd($shop)--}}
                            <tr>
{{--                                <td class="font-w600">--}}
{{--                                    <a href="{{ route('admin.show.user', $user->id) }}" > {{ $user->name}}</a>--}}
{{--                                </td>--}}
                                <td class="font-w600">
                                    {{ $shop->shop_domain}}
                                </td>
                                <td class="font-w600">
                                    {{ $shop->shop_type}}
                                </td>
                                <?php
                                    $wordpress_products = \App\WordpressProduct::where('shop_id', $shop->id)->count();
                                    $shopify_products = \App\ShopifyProduct::where('shop_id', $shop->id)->count();
                                ?>
                                <td class="font-w600">
                                    @if($wordpress_products != null )
                                        {{$wordpress_products}}
                                    @elseif($shopify_products != null)
                                        {{$shopify_products}}
                                    @endif
                                </td>
                                <?php
                                $wordpress_orders = \App\WordpressOrder::where('shop_id', $shop->id)->count();
                                $shopify_orders = \App\ShopifyOrder::count();
                                ?>
                                <td class="font-w600">
                                    @if($wordpress_orders != 0 )
                                        {{$wordpress_orders}}
                                    @elseif($shopify_orders !=0)
                                        {{$shopify_orders}}
                                    @endif
                                </td>

                                <td >
                                    <div class="btn-group " >
                                        <form action="{{Route('sync-wordpress-products', $shop->id)}}" method="post">
                                            @csrf
                                            <button style="font-size: 13px;" type="submit" class="btn btn-primary ">Sync Product</button>
                                        </form>
                                        <form action="{{Route('sync-wordpress-orders', $shop->id)}}" method="post">
                                            @csrf
                                            <button style="font-size: 13px;" class="btn btn-warning " type="submit">Sync Order</button>
                                        </form>
                                        </div>
                                </td>
                                <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <button type="button" data-toggle="modal" data-target="#editModal{{$key}}" class="btn btn-success btn-sm" >
                                            <i class="fa fa-fw fa-pen-alt"></i>
                                        </button>

{{--                                        <button type="button" data-toggle="modal" data-target="#deleteModal{{$key}}" class="btn btn-danger btn-sm" >--}}
{{--                                            <i class="fa fa-fw fa-trash-alt"></i>--}}
{{--                                        </button>--}}

{{--                                        <div class="modal fade" id="deleteModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--                                            <div class="modal-dialog" role="document">--}}
{{--                                                <div class="modal-content">--}}
{{--                                                    <div class="modal-header">--}}
{{--                                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>--}}
{{--                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                            <span aria-hidden="true">&times;</span>--}}
{{--                                                        </button>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="modal-body">--}}
{{--                                                        You are going to delete the shop--}}
{{--                                                    </div>--}}
{{--                                                    <div class="modal-footer">--}}
{{--                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                                        <form action="" method="POST" >--}}
{{--                                                            {{route('shops.destroy', $shop->id)}}--}}
{{--                                                            @csrf--}}
{{--                                                            @method('DELETE')--}}
{{--                                                            <button type="submit" class="btn btn-danger">Delete</button>--}}
{{--                                                        </form>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="modal fade" id="editModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-bottom border-light">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Shop</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('shops.update', $shop->id) }}" method="POST" class="row justify-content-center">
                                                        @csrf

                                                        <div class="col-md-12">
                                                            <div class="block">

                                                                <div class="block-content block-content-full">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">

                                                                            <div class="form-group">
                                                                                <label for="">API key</label>
                                                                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" id="" name="api_key" placeholder="Enter API key.." value="{{ old('api_key', $shop->api_key) }}">
                                                                                @error('api_key')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="">API secret</label>
                                                                                <input type="text" class="form-control @error('api_secret') is-invalid @enderror" id="" name="api_secret" placeholder="Enter API secret.." value="{{ old('api_secret', $shop->api_secret) }}">
                                                                                @error('api_secret')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="">API password</label>
                                                                                <input type="text" class="form-control @error('api_password') is-invalid @enderror" id="" name="api_password" placeholder="Enter API password.." value="{{ old('api_password', $shop->api_password) }}">
                                                                                @error('api_password')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="">API version</label>
                                                                                <input type="text" class="form-control @error('api_version') is-invalid @enderror" id="" name="api_version" placeholder="Enter API version.." value="{{ old('api_version', $shop->api_version) }}">
                                                                                @error('api_version')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>


                                                                            <div class="form-group d-flex justify-content-end">
                                                                                <button type="submit" class="btn btn-primary">Update Shop</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

{{--                                                    <form action="" method="POST">--}}
{{--                                                        @csrf--}}
{{--                                                        @method('PUT')--}}
{{--                                                        <div class="modal-body">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="">Name</label>--}}
{{--                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="" name="name" placeholder="Enter team name.." >--}}
{{--                                                                @error('name')--}}
{{--                                                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                                @enderror--}}
{{--                                                            </div>--}}

{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="">Email</label>--}}
{{--                                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="" name="email" placeholder="Enter team email.." value="{{ $user->email }}">--}}
{{--                                                                @error('email')--}}
{{--                                                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                                @enderror--}}
{{--                                                            </div>--}}

{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="">Password</label>--}}
{{--                                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="" name="password" placeholder="Enter password.." >--}}
{{--                                                                @error('password')--}}
{{--                                                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                                @enderror--}}
{{--                                                            </div>--}}

{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="">Confirm password</label>--}}
{{--                                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="" name="password_confirmation" placeholder="Re-enter password.." >--}}
{{--                                                                @error('password_confirmation')--}}
{{--                                                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                                @enderror--}}
{{--                                                            </div>--}}

{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="">Type</label>--}}

{{--                                                                <select type="text" class="form-control @error('type') is-invalid @enderror js-select2"  name="type[]" style="width: 100%;" data-placeholder="Choose Role.." multiple>--}}

{{--                                                                    @if($user->hasRole('shipping_team') && $user->hasRole('outsource_team'))--}}
{{--                                                                        <option value="shipping_team" selected >Shipping Team</option>--}}
{{--                                                                        <option value="outsource_team" selected>Source Team</option>--}}
{{--                                                                    @elseif($user->hasRole('outsource_team'))--}}
{{--                                                                        <option value="shipping_team" >Shipping Team</option>--}}
{{--                                                                        <option value="outsource_team" selected>Source Team</option>--}}
{{--                                                                    @else--}}
{{--                                                                        <option value="shipping_team" selected>Shipping Team</option>--}}
{{--                                                                        <option value="outsource_team">Source Team</option>--}}
{{--                                                                    @endif--}}



{{--                                                                </select>--}}
{{--                                                                @error('type')--}}
{{--                                                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                                                @enderror--}}
{{--                                                            </div>--}}

{{--                                                        </div>--}}
{{--                                                        <div class="modal-footer border-top border-light">--}}
{{--                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                                            <button type="submit" class="btn btn-primary" >Update</button>--}}
{{--                                                        </div>--}}
{{--                                                    </form>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
{{--                        {{ $users->links() }}--}}
                    </div>
{{--                @else--}}
{{--                    No data!--}}
{{--                @endif--}}
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->


    <div class="modal fade" id="createModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title" id="exampleModalLabel">Add a User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form  method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  name="name" placeholder="Enter team name..">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"  name="email" placeholder="Enter team email.." >
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"  name="password" placeholder="Enter password.." >
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Confirm password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Re-enter password.." >
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Type</label>
                            <select class="@error('type') is-invalid @enderror js-select2 form-control" name="type[]" style="width: 100%;" data-placeholder="Choose Role.." multiple>
                                <option ></option>
                                <option value="shipping_team">Shipping Team</option>
                                <option value="outsource_team">Source Team</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer border-top border-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
