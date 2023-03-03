@extends('layouts.layout')

@section('title','Stock Items')
@section('page-title','Stock Items')

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
	<div class="alert alert-danger" style="color: white;">
		<b>
	    {{ session('error') }}
	    </b>
	</div>
	@endif
</div>
<div class="container-fluid py-4">
  <div class="row">
  	<div class="col-12 mb-3">
  		<tr>
  			<th>Filter by : </th>
  			<td>
  				<a href="{{ url('/stock-item')}}" class="badge badge-sm bg-gradient-secondary">All</a>
  			</td>
  			<td>
  				<a href="{{ url('/stock-item?category_order=Online')}}" class="badge badge-sm bg-gradient-success">Online</a>
  			</td>
  			<td>
  				<a href="{{ url('/stock-item?category_order=Offline')}}" class="badge badge-sm bg-gradient-danger">Offline</a>
  			</td>
  		</tr>
  	</div>
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 mb-3">
          <h6 class="float-start">Stock Items</h6>
          <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addItem">Add Item</button>
        </div>
        <!-- Modal -->
		<div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
		  <div class="modal-dialog modal-md vertical-align-center">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <form action="{{ route('stock.store')}}" method="POST">
		        	@csrf
		        	<div class="mb-3">
					  <label for="nameItem" class="form-label">Name Item</label>
					  <input type="text" class="form-control" id="nameItem" name="name_item">
					</div>
					<div class="mb-3">
						<label for="categoryItems" class="form-label">Category Item</label>
						<select class="form-select" name="category_item">
							<option selected>Open this select menu</option>
						  @forelse($category_items as $category_item)
						  <option value="{{ $category_item->id }}" >{{ $category_item->name_category}}</option>
						  @empty
						  <option>Data empty</option>
						  @endforelse
						</select>
					</div>
					<div class="mb-3">
						<label for="categoryOrder" class="form-label">Category Order</label>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="category_order" id="exampleRadios1" value="Online">
						  <label class="form-check-label" for="exampleRadios1">
						    Online
						  </label>
						</div>
						<div class="form-check">
						  <input class="form-check-input" type="radio" name="category_order" id="exampleRadios2" value="Offline">
						  <label class="form-check-label" for="exampleRadios2">
						    Offline
						  </label>
						</div>
					</div>
					<div class="mb-3">
					  <label for="quantity" class="form-label">Quantity</label>
					  <input type="number" class="form-control" id="quantity" name="quantity">
					</div>
					<div class="mb-3">
					  <label for="price" class="form-label">Price</label>
					  <input type="number" class="form-control" id="price" name="price">
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
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center justify-content-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Code Item</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Name Item</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Category Item</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Category Order</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Quantity</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Price</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">Options</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              	@forelse ($stock_items as $stock_item)
                <tr>
                  <td style="color: black;">
                    {{ $stock_item->code_item}}
                  </td>
                  <td style="color: black;">
                    {{ $stock_item->name_item}}
                  </td>
                  <td style="color: black;">
                    {{ $category_items->where('id', $stock_item->category_item)->first()->name_category }}
                  </td>
                  <td style="color: black;">
                  	@if ($stock_item->category_order == 'Online')
                  	<span class="badge badge-sm bg-gradient-success">{{ $stock_item->category_order }}</span>
                  	@else
                  	<span class="badge badge-sm bg-gradient-warning">{{ $stock_item->category_order }}</span>
                  	@endif
                  </td>
                  <td style="color: black;">
                    {{ $stock_item->quantity}}
                  </td>
                  <td style="color: black;">
                    @currency($stock_item->price)
                  </td>
                  <td class="align-middle" style="color: black;">
                  	<form onsubmit="return confirm('Are your sure?');" action="{{ route('stock.destroy', $stock_item->id)}}" method="POST">
                  		@csrf
                        @method('DELETE')
	                  	<a data-bs-toggle="modal" data-bs-target="#edit-{{ $stock_item->id }}" class="badge badge-sm bg-gradient-primary">Edit</a>
	                  	<button type="submit" class="badge badge-sm bg-gradient-danger" style="border: none;">Delete</button>
                  	</form>
                  </td>
                </tr>
                @empty
                <tr>
                	<td colspan="7" class="mt-5">
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

@foreach( $stock_items as $stock_item)
<!-- Modal -->
<div class="modal fade" id="edit-{{ $stock_item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data | {{ $stock_item->name_item }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('stock.update', $stock_item->id)}}" method="POST">
        	@csrf
        	@method('PUT')
        	<div class="mb-3">
			  <label for="nameItem" class="form-label">Name Item</label>
			  <input type="text" class="form-control @error('name_item') is-invalid @enderror" id="nameItem" name="name_item" value="{{ $stock_item->name_item}}">
			</div>
			<div class="mb-3">
				<label for="categoryItems" class="form-label">Category Item</label>
				<select class="form-select @error('category_item') is-invalid @enderror" name="category_item">
				  <option selected>Open this select menu</option>
				  @forelse($category_items as $category_item)
				  <option value="{{ $category_item->id }}" {{ $stock_item->category_item == $category_item->id ? 'selected' : ''}}>{{ $category_item->name_category}}</option>
				  @empty
				  <option>Data empty</option>
				  @endforelse
				</select>
			</div>
			<div class="mb-3">
				<label for="categoryOrder" class="form-label">Category Order</label>
				<div class="form-check">
				  <input class="form-check-input @error('category_order') is-invalid @enderror" type="radio" name="category_order" id="exampleRadios1" value="Online" {{ $stock_item->category_order == "Online" ? 'checked' : ''}}>
				  <label class="form-check-label" for="exampleRadios1">
				    Online
				  </label>
				</div>
				<div class="form-check">
				  <input class="form-check-input @error('category_order') is-invalid @enderror" type="radio" name="category_order" id="exampleRadios2" value="Offline" {{ $stock_item->category_order == "Offline" ? 'checked' : ''}}>
				  <label class="form-check-label" for="exampleRadios2">
				    Offline
				  </label>
				</div>
			</div>
			<div class="mb-3">
			  <label for="quantity" class="form-label">Quantity</label>
			  <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ $stock_item->quantity}}">
			</div>
			<div class="mb-3">
			  <label for="price" class="form-label">Price</label>
			  <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $stock_item->price}}">
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