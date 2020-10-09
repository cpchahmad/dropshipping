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
        <div class="content content-full py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                <h1 class="flex-sm-fill h3 my-2">
                    Vendors
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('vendors.index') }}">Vendors</a>
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
            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#createModel">Add Vendor</a>
        </div>

    <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-header">
                <h3 class="block-title">Vendors Table</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                @if(count($vendors) > 0)
                <table class="table table-striped table-vcenter">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Website</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                            @foreach($vendors as $vendor)
                                <tr>
                                    <td class="font-w600">
                                        {{ $vendor->name}}
                                    </td>
                                    <td class="font-w600">
                                        @if($vendor->url)
                                            <a href="{{ $vendor->url }}" target=_blank" >Visit Website</a>
                                        @endif
                                    </td>
                                    <td class="d-flex justify-content-end ">
                                       <div class="btn-group">
                                           <button type="button" data-toggle="modal" data-target="#editModal{{ $vendor->id }}" class="btn btn-success btn-sm" >
                                               <i class="fa fa-fw fa-pen"></i>
                                           </button>
                                           <button type="button" data-toggle="modal" data-target="#deleteModal{{ $vendor->id }}" class="btn btn-danger btn-sm" >
                                               <i class="fa fa-fw fa-trash-alt"></i>
                                           </button>
                                       </div>


                                        <div class="modal fade" id="editModal{{$vendor->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit a Vendor</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="#">Vendor Name</label>
                                                                <input placeholder="Enter vendor name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $vendor->name }}" >
                                                                @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="#">Vendor Website URL</label>
                                                                <input placeholder="Enter vendor website url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $vendor->url }}" >
                                                                @error('url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" >Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="deleteModal{{$vendor->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                        <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                    </tbody>

                    <div class="d-flex justify-content-end">
                        {{ $vendors->links() }}
                    </div>
                </table>
                @else
                    No data!
                @endif
            </div>
        </div>
        <!-- END Dynamic Table Full -->

    </div>
    <!-- END Page Content -->


    <!-- Create modal -->
    <div class="modal fade" id="createModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('vendors.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="#">Vendor Name</label>
                                <input placeholder="Enter vendor name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"  >
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="#">Vendor Website URL</label>
                                <input placeholder="Enter vendor website url" type="text" class="form-control @error('url') is-invalid @enderror" name="url"  >
                                @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Add</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
