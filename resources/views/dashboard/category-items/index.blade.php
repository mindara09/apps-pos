@extends('layouts.layout')

@section('title','Category Items')
@section('page-title','Category Items')

@section('content')
<!-- Notifikasi menggunakan flash session data -->
<div class="container-fluid">
	@if (session('success'))
	<div class="alert alert-success" style="color: white;">
		<b>
	    {{ session('success') }}
	    </b>
	</div>
	@endif

	@if (session('error'))
	<div class="alert alert-error" style="color: white;">
		<b>
	    {{ session('error') }}
	    </b>
	</div>
	@endif
</div>
<div class="container-fluid">
	<div class="container-fluid py-4">
	  <div class="row">
	    <div class="col-12">
	      <div class="card mb-4">
	        <div class="card-header pb-0 mb-3">
	          	<h6 class="float-start">Category Items</h6>
          		<button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addItem">Add Item</button>
          		<!-- Modal -->
				<div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
				  <div class="modal-dialog modal-md vertical-align-center">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
				        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				      </div>
				      <div class="modal-body">
				        <form action="{{ route('category.store')}}" method="POST">
				        	@csrf
				        	<div class="mb-3">
							  <label for="nameCategory" class="form-label">Name Item</label>
							  <input type="text" class="form-control @error('name_category') is-invalid @enderror" id="nameCategory" name="name_category" required>
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
	        <div class="card-body px-0 pt-0 pb-2">
	          <div class="table-responsive p-0">
	            <table class="table align-items-center justify-content-center mb-0">
	              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Category Item</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Options</th>
                  <th></th>
                </tr>
              </thead>
	              <tbody>
	              	@forelse ($category_items as $category_item)
	              	<tr>
	              		<td style="color: black;">
	              			{{ $category_item->name_category }}
	              		</td>
	              		<td>
	              			<form onsubmit="return confirm('Are your sure?');" action="{{ route('category.destroy', $category_item->id)}}" method="POST">
		                  		@csrf
		                        @method('DELETE')
			                  	<a data-bs-toggle="modal" data-bs-target="#edit-{{ $category_item->id }}" class="badge badge-sm bg-gradient-primary">Edit</a>
			                  	<button type="submit" class="badge badge-sm bg-gradient-danger" style="border: none;">Delete</button>
		                  	</form>
	              		</td>
	              	</tr>
	              	@empty
	              	<tr>
	              		<td colspan="2" class="mt-1">
	              			<center>
                				Data empty
                			</center>
	              		</td>
	              	</tr>
	              	@endforelse
	              </tbody>
	            </table>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
</div>
@foreach( $category_items as $category_item)
<!-- Modal -->
<div class="modal fade" id="edit-{{ $category_item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data | {{ $category_item->name_item }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('category.update', $category_item->id)}}" method="POST">
        	@csrf
        	@method('PUT')
        	<div class="mb-3">
			  <label for="nameCategory" class="form-label">Name Category</label>
			  <input type="text" class="form-control @error('name_category') is-invalid @enderror" id="nameCategory" name="name_category" value="{{ $category_item->name_category }}"required>
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