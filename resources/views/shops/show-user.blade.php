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
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Add User
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('admin.users') }}">Add User</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Main Container -->
    <main id="main-container pt-0">
        <!-- Hero -->
        <div class="bg-image" style="background-image: url({{ asset('media/photos/photo8@2x.jpg') }});">
            <div class="bg-black-50">
                <div class="content content-full text-center">
                    <div class="my-3">
                        <img class="img-avatar img-avatar-thumb" src="{{ asset('media/avatars/avatar13.jpg') }}" alt="">
                    </div>
                    <h1 class="h2 text-white mb-0">{{ $user->name }}</h1>
                    <span class="text-white-75">{{ $user->roleName }}</span>
                </div>
            </div>
        </div>
        <!-- END Hero -->

        <!-- Stats -->
        @if($user->roleName == "Source Team")
            <div class="bg-white border-bottom">
                <div class="content content-boxed">
                    <div class="row items-push text-center">
                        <div class="col-6 col-md-4">
                            <div class="font-size-sm font-w600 text-muted text-uppercase">Products</div>
                            <span class="link-fx font-size-h3" >{{ $user->product_count }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="font-size-sm font-w600 text-muted text-uppercase">Approved Products</div>
                            <span class="link-fx font-size-h3" >{{ $user->approve_product_count }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="font-size-sm font-w600 text-muted text-uppercase">Unapproved Products</div>
                            <span class="link-fx font-size-h3" >{{ $user->unapprove_product_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- END Stats -->

        <!-- Page Content -->
        <div class="content content-boxed">
            <div class="row">
                <div class="col-md-7 col-xl-8">
                    @if($user->roleName == "Source Team")
                    <!-- Products -->
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                <i class="fa fa-briefcase text-muted mr-1"></i> Products
                            </h3>
                        </div>
                        @if(count($products) >0)
                            <div class="block-content">
                                @foreach($products as $product)
                                    <div class="media d-flex align-items-center push">
                                        <div class="mr-3">
                                            <span class="item item-rounded" >
                                                <img src="{{ $product->image }}" class="w-100" alt="">
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="font-w600"><a href="{{ route('products.show', $product->id) }}">{{ $product->title}}</a></div>
                                            <div class="font-size-sm">Approved: {{ $product->approved_status }}</div>
                                        </div>
                                    </div>
                                @endforeach

                                <div>
                                    {{ $products->links() }}
                                </div>
                            </div>
                        @else
                            <p>No data!</p>
                        @endif
                    </div>
                    <!-- END Products -->
                    @else
                    <!-- Orders -->
{{--                    <div class="block">--}}
{{--                        <div class="block-header block-header-default">--}}
{{--                            <h3 class="block-title">--}}
{{--                                <i class="fa fa-briefcase text-muted mr-1"></i> O--}}
{{--                            </h3>--}}
{{--                        </div>--}}
{{--                        @if(count($products) >0)--}}
{{--                            <div class="block-content">--}}
{{--                                @foreach($products as $product)--}}
{{--                                    <div class="media d-flex align-items-center push">--}}
{{--                                        <div class="mr-3">--}}
{{--                                    <span class="item item-rounded" >--}}
{{--                                        <img src="{{ asset('storage/'.$product->image) }}" class="w-100" alt="">--}}
{{--                                    </span>--}}
{{--                                        </div>--}}
{{--                                        <div class="media-body">--}}
{{--                                            <div class="font-w600"><a href="{{ route('products.show', $product->id) }}">{{ $product->title}}</a></div>--}}
{{--                                            <div class="font-size-sm">Approved: {{ $product->approved_status }}</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}

{{--                                <div>--}}
{{--                                    {{ $products->links() }}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <p>No data!</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}
                    <!-- END Orders -->
                    @endif


                    <!-- Logs -->
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                <i class="fa fa-list text-muted mr-1"></i> Logs
                            </h3>
                        </div>

                        @if(count($details) >0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Login Time</th>
                                        <th>Login Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $detail)
                                      <tr>
                                          <td>{{ $detail->date }}</td>
                                          <td>{{ $detail->location }}</td>
                                      </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No data!</p>
                        @endif
                    </div>
                    <!-- END Logs -->
                </div>
                <div class="col-md-5 col-xl-4">
                    <!-- Updates -->
                    <ul class="timeline timeline-alt py-0">
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-default">
                                <i class="fa fa-search"></i>
                            </div>
                            <div class="timeline-event-block block invisible" data-toggle="appear">
                                <div class="block-header">
                                    <h3 class="block-title">Last Login</h3>

                                </div>
                                <div class="block-content">
                                    <p class="font-w600 mb-2">
                                        {{ $user->date }}
                                    </p>
                                    <p>
                                        {{ $user->location }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        @if($product)
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-modern">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            @if($user->roleName == "Source Team")
                            <div class="timeline-event-block block invisible" data-toggle="appear">
                                <div class="block-header">
                                    <h3 class="block-title">Last product added</h3>
                                    <div class="block-options">
                                        <div class="timeline-event-time block-options-item font-size-sm">
                                            {{ $product->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content block-content-full">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <span class="item item-rounded" >
                                                <img src="{{ $product->image }}" class="w-100" alt="">
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="font-w600"><a href="{{ route('products.show', $product->id) }}">{{ $product->title}}</a></div>
                                            <div class="font-size-sm">Approved: {{ $product->approved_status }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </li>
                            @endif
                    </ul>
                    <!-- END Updates -->
                </div>

            </div>
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->


@endsection
