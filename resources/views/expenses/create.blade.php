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
                    {{ isset($expense) ? 'Edit Expense' : 'Add Expense' }}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('expenses.create') }}"> Create Expense</a>
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
            <form action="{{ isset($expense) ? route('expenses.update', $expense->id) : route('expenses.store') }}" method="POST" class="row justify-content-center">
                @csrf
                @isset($expense)
                    @method('PUT')
                @endisset
                <div class="col-md-12">
                    <div class="block">

                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                            <label for="">Expense Title</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="" name="title" placeholder="Enter expense title.." value="{{ isset($expense) ? $expense->title : '' }}">
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    <div class="form-group">
                                        <label for="">Notes</label>
                                        <textarea cols="30" rows="10" class="form-control @error('notes') is-invalid @enderror" id="" name="notes" placeholder="Enter notes..">{{ isset($expense) ? $expense->notes  : '' }}</textarea>
                                        @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <label for="">Price</label>
                                                <input type="text" class="form-control @error('price') is-invalid @enderror" id="" name="price" placeholder="Enter price.." value="{{ isset($expense) ? is_null($expense->rmb_price) ? number_format($expense->usd_price, 2) : number_format($expense->rmb_price, 2) : '' }}">
                                                @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Currency</label>
                                                <select name="currency" id="" class="form-control">
                                                    @isset($expense)
                                                        @if($expense->currency === "usd")
                                                            <option selected value="usd">USD</option>
                                                            <option value="rmb">RMB</option>

                                                        @else
                                                            <option value="usd">USD</option>
                                                            <option selected value="rmb">RMB</option>

                                                        @endif
                                                    @endisset
                                                    <option value="usd">USD</option>
                                                    <option value="rmb">RMB</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Category</label>
                                        <select name="category" id="" class="form-control">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                   @if(isset($expense))
                                                       @if($category->id === $expense->$category)
                                                            selected
                                                       @endif
                                                   @endif
                                                >{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ isset($expense) ? 'Update Expense' : 'Add Expense'}}</button>
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
