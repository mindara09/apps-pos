@extends('layouts.layout')

@section('title','User Managements')
@section('page-title','User Managements')

@section('content')
<div class="container-fluid">
  <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
    <span class="mask bg-gradient-primary opacity-6"></span>
  </div>
  <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
    <div class="row gx-4">
      <div class="col-auto">
        <div class="avatar avatar-xl position-relative">
          <img src="{{ url('/assets/img/icon-user.jpg') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
        </div>
      </div>
      <div class="col-auto my-auto">
        <div class="h-100">
          <h5 class="mb-0">
            {{ auth()->user()->name }}
          </h5>
          <p class="mb-0 font-weight-bold text-md">
            {{ auth()->user()->username}}
          </p>
          <p class="mb-0 font-weight-bold text-sm">
            {{ auth()->user()->role_user}}
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Notifikasi menggunakan flash session data -->
<div class="container-fluid mt-3">
	@if (session('success'))
	<div class="alert alert-success" style="color: white;">
		<b>
	    {{ session('success') }}
	    </b>
	</div>
	@endif

	@if (session('error'))
	<div class="alert alert-danger" style="color: white;">
		<b>
	    {{ session('error') }}
	    </b>
	</div>
	@endif
</div>
<div class="container-fluid py-4">
  <div class="row">
  	@if (auth()->user()->role_user == "Owner")
    <div class="col-12 col-xl-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <h6 class="mb-0">Log Users</h6>
        </div>
        <div class="card-body p-3">
          <ul class="list-group">
          	@forelse( $logs as $log)
          	<li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 class="mb-0 text-sm">{{ $log->action }}</h6>
                <p class="mb-0 text-xs">{{ $users->where('id', $log->user_id)->first()->name ?? null }}</p>
              </div>
              <p class="mb-0 text-sm ms-auto">{{ $log->created_at }}</p>
            </li>
            @empty
            <center>
            	<h6 class="mt-2 text-secondary">
            		Data Users Empty
            	</h6>
            </center>
            @endforelse
        
        	{{ $logs->onEachSide(1)->links('custom-pagination')}}
          </ul>
        </div>
      </div>
    </div>
    @else
    <div class="col-12 col-xl-12">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <h6 class="mb-0">Log Users</h6>
        </div>
        <div class="card-body p-3">
          <ul class="list-group">
          	@forelse( $logs->where('user_id', auth()->user()->id) as $log)
          	<li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 class="mb-0 text-sm">{{ $log->action }}</h6>
                <p class="mb-0 text-xs">{{ $users->where('id', $log->user_id)->first()->name ?? null }}</p>
              </div>
              <p class="mb-0 text-sm ms-auto">{{ $log->created_at }}</p>
            </li>
            @empty
            <center>
            	<h6 class="mt-2 text-secondary">
            		Data Users Empty
            	</h6>
            </center>
            @endforelse
        
        	{{ $logs->onEachSide(1)->links('custom-pagination')}}
          </ul>
        </div>
      </div>
    </div>
    @endif
    @if(auth()->user()->role_user == "Owner")
    <div class="col-12 col-xl-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="row">
            <div class="col-md-8 d-flex align-items-center">
              <h6 class="mb-0">Profile Information</h6>
            </div>
            <div class="col-md-4 text-end">
              <a href="javascript:;">
                <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
        	<form action="{{ route('update.user')}}" method="POST">
        		@csrf
	        	<div class="mb-3">
				  <label for="nameItem" class="form-label">Name User</label>
				  <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameItem" name="name" value="{{ auth()->user()->name}}">
				</div>
				<div class="mb-3">
				  <label for="username" class="form-label">Username</label>
				  <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ auth()->user()->username}}">
				</div>
				<div class="mb-3">
				  <label for="password" class="form-label">Password</label>
				  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="*******">
				</div>
				<div class="mb-3">
				  <label for="confirm_password" class="form-label">Confirm Password</label>
				  <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password" placeholder="*******">
				</div>
				<button type="submit" class="btn btn-info float-end">Save changes</button>
			</form>
        </div>
      </div>
    </div>
    @endif

    @if (auth()->user()->role_user == "Owner")
    <div class="col-12 col-xl-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <h6 class="mb-0 float-start">User Management</h6>
          <button class="badge badge-sm bg-primary float-end" data-bs-toggle="modal" data-bs-target="#addItem">Add Users</button>
          <!-- Modal -->
			<div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
			  <div class="modal-dialog modal-md vertical-align-center">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
			        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			      </div>
			      <div class="modal-body">
			        <form action="{{ route('users.store')}}" method="POST">
			        	@csrf
			        	<div class="mb-3">
							<label for="categoryOrder" class="form-label">Role User</label>
							<div class="form-check">
							  <input class="form-check-input @error('role_user') is-invalid @enderror" type="radio" name="role_user" id="exampleRadios1" value="Owner">
							  <label class="form-check-label" for="exampleRadios1">
							    Owner
							  </label>
							</div>
							<div class="form-check">
							  <input class="form-check-input @error('role_user') is-invalid @enderror" type="radio" name="role_user" id="exampleRadios2" value="Cashier">
							  <label class="form-check-label" for="exampleRadios2">
							    Cashier
							  </label>
							</div>
						</div>
			        	<div class="mb-3">
						  <label for="nameItem" class="form-label">Name User</label>
						  <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameItem" name="name">
						</div>
						<div class="mb-3">
						  <label for="username" class="form-label">Username</label>
						  <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username">
						</div>
						<div class="mb-3">
						  <label for="password" class="form-label">Password</label>
						  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
						</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary">Save changes</button>
			    </form>
			      </div>
			    </div>
			  </div>
			</div>
        </div>
        <div class="card-body p-3">
          <ul class="list-group">
          	@forelse ($users as $user)
          	@if (auth()->user()->id != $user->id)
            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
              <div class="avatar me-3">
                <img src="{{ url('/assets/img/icon-user.jpg') }}" alt="kal" class="border-radius-lg shadow">
              </div>
              <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                <p class="mb-0 text-xs">{{ $user->username }}</p>
                <p class="mb-0 text-xxs">{{ $user->role_user }}</p>
              </div>
              <a class="badge badge-sm bg-gradient-primary ms-auto" data-bs-toggle="modal" data-bs-target="#edit-{{ $user->id }}">Edit</a>
	          <form onsubmit="return confirm('Are your sure?');" action="{{ route('users.destroy', $user->id)}}" method="POST">
	          	@csrf
	            @method('DELETE')
	            <button type="submit" class="badge badge-sm bg-gradient-danger ms-1" style="border: none;">Delete</button>
	          </form>
            </li>
            @endif
            @empty
            <center>
            	<h6 class="mt-5 text-secondary">
            		Data Users Empty
            	</h6>
            </center>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
@foreach($users as $user)
<div class="modal fade" id="edit-{{ $user->id}}" tabindex="-1" aria-labelledby="edit-{{ $user->id}}" aria-hidden="true">
  <div class="modal-dialog modal-md vertical-align-center">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Users</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('users.update', $user->id)}}" method="POST">
        	@csrf
        	@method('PUT')
        	<div class="mb-3">
				<label for="categoryOrder" class="form-label">Role User</label>
				<div class="form-check">
				  <input class="form-check-input @error('role_user') is-invalid @enderror" type="radio" name="role_user" id="exampleRadios1" value="Owner" {{ $user->role_user == "Owner" ? 'checked' : ''}}>
				  <label class="form-check-label" for="exampleRadios1">
				    Owner
				  </label>
				</div>
				<div class="form-check">
				  <input class="form-check-input @error('role_user') is-invalid @enderror" type="radio" name="role_user" id="exampleRadios2" value="Cashier" {{ $user->role_user == "Cashier" ? 'checked' : ''}}>
				  <label class="form-check-label" for="exampleRadios2">
				    Cashier
				  </label>
				</div>
			</div>
        	<div class="mb-3">
			  <label for="nameItem" class="form-label">Name User</label>
			  <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameItem" name="name" value="{{ $user->name}}" required>
			</div>
			<div class="mb-3">
			  <label for="username" class="form-label">Username</label>
			  <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ $user->username}}" required>
			</div>
			<div class="mb-3">
			  <label for="password" class="form-label">Password</label>
			  <input type="password" class="form-control" id="password" name="password" placeholder="*********">
			</div>
			<div class="mb-3">
			  <label for="confirm_password" class="form-label">Confirm Password</label>
			  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="*********">
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection
@section('js')
@endsection