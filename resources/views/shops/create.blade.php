@extends('layouts.backend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">
@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Setup a Shop
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('shops.create') }}"> Shop Settings</a>
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
            <form action="{{ route('shops.store') }}" method="POST" class="row justify-content-center">
                @csrf

                <div class="col-md-12">
                    <div class="block">

                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                            <label for="">Shop Domain</label>
                                            <input type="text" class="form-control @error('shop_domain') is-invalid @enderror" id="" name="shop_domain" placeholder="Enter shop domain.." value="{{ old('shop_domain', $shop->shop_domain) }}">
                                            @error('shop_domain')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

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
                                        <button type="submit" class="btn btn-primary">Add Shop</button>
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
