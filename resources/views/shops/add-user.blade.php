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
                    Add User
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('admin.users') }}">Users</a>
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
            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#createModel">Add User</a>
        </div>

        <!-- Dynamic Table Full -->
        <div class="block mt-3">
            <div class="block-header">
                <h3 class="block-title">Users</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/tables_datatables.js -->
                @if(count($users) > 0)
                    <table class="table table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $user)
                            <tr>
                                <td class="font-w600">
                                    <a href="{{ route('admin.show.user', $user->id) }}" > {{ $user->name}}</a>
                                </td>
                                <td class="font-w600">
                                    {{ $user->email}}
                                </td>
                                <td class="font-w600">
                                    {{ $user->role }}
                                </td>
                                <td class="d-flex justify-content-end">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-alt-primary js-tooltip-enabled" href="{{ route('admin.show.user', $user->id) }}" data-toggle="tooltip" title="" data-original-title="View">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        <button type="button" data-toggle="modal" data-target="#editModal{{ $user->id }}" class="btn btn-success btn-sm" >
                                            <i class="fa fa-fw fa-pen-alt"></i>
                                        </button>

                                        <button type="button" data-toggle="modal" data-target="#deleteModal{{ $user->id }}" class="btn btn-danger btn-sm" >
                                            <i class="fa fa-fw fa-trash-alt"></i>
                                        </button>



                                        <div class="modal fade" id="deleteModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        You are going to delete the user
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="editModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-bottom border-light">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit a User</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('admin.edit.user', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="">Name</label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="" name="name" placeholder="Enter team name.." value="{{ $user->name }}">
                                                                @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">Email</label>
                                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="" name="email" placeholder="Enter team email.." value="{{ $user->email }}">
                                                                @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">Password</label>
                                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="" name="password" placeholder="Enter password.." >
                                                                @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">Confirm password</label>
                                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="" name="password_confirmation" placeholder="Re-enter password.." >
                                                                @error('password_confirmation')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="">Type</label>
                                                                <select type="text" class="form-control @error('type') is-invalid @enderror" id="" name="type">

                                                                    @if($user->hasRole('shipping_team'))
                                                                        <option value="shipping_team" selected>Shipping Team</option>
                                                                        <option value="outsource_team">Source Team</option>
                                                                    @else
                                                                        <option value="shipping_team" >Shipping Team</option>
                                                                        <option value="outsource_team" selected>Source Team</option>
                                                                    @endif



                                                                </select>
                                                                @error('type')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer border-top border-light">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" >Update</button>
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
                    <div class="d-flex justify-content-end">
                        {{ $users->links() }}
                    </div>
                @else
                    No data!
                @endif
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

                <form action="{{ route('admin.store.user') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="" name="name" placeholder="Enter team name..">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="" name="email" placeholder="Enter team email.." >
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="" name="password" placeholder="Enter password.." >
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Confirm password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="" name="password_confirmation" placeholder="Re-enter password.." >
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Type</label>
                            <select type="text" class="form-control @error('type') is-invalid @enderror" id="" name="type">
                                <option value="" disabled selected>Select user type</option>
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
