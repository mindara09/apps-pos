@extends('layouts.layout')

@section('title','Transactions')
@section('page-title','Transactions')

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

<!-- Table Data -->
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="float-start">
          	<h5>Table Transactions</h5>
          	<p class="text-xs">Filter By :</p>
          </div>
          <a href="{{ url('/transactions/new')}}" class="float-end btn btn-sm btn-primary">Add Transactions</a>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name Customer</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Code Transaction</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Orders</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notes</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cash Customer</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Change</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Options</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                <tr>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $item->name_customer }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $item->code_transaction }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">
                      @foreach(json_decode($item->orders) as $x => $val)
                        <p style="font-size: 10px;">
                          Order : {{ $stock_items->where('id', $x)->first()->name_item }}<br>
                          Qty : {{ $val->qty }} <br>
                          Price : {{ $val->qty * $val->price}}
                        </p>
                      @endforeach
                    </p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">
                      {{ $item->payment }}
                    </p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">
                      {{ $item->notes }}
                    </p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($item->status == 'Open Bill')
                    <span class="badge badge-sm bg-gradient-danger">{{ $item->status }}</span>
                    @else
                    <span class="badge badge-sm bg-gradient-success">{{ $item->status }}</span>
                    @endif
                  </td>
                  <td>
                    <center>
                      <p class="text-xs font-weight-bold mb-0">
                        @currency($item->cash_customer)
                      </p>
                    </center>
                  </td>
                  <td>
                    <center>
                      <p class="text-xs font-weight-bold mb-0">
                        @currency($item->total)
                      </p>
                    </center>
                  </td>
                  <td>
                    <center>
                      <p class="text-xs font-weight-bold mb-0">
                        @currency($item->change)
                      </p>
                    </center>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $item->created_at }}</span>
                  </td>
                  
                  <td class="align-middle">
                    <a class="btn btn-link text-dark mb-0" href="{{ route('transaction.edit_orders', $item->id)}}" ><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                    <form onsubmit="return confirm('Are your sure?');" action="{{ route('transaction.delete_orders', $item->id)}}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-link text-danger text-gradient mb-0" style="border: none;"><i class="far fa-trash-alt me-2"></i>Delete</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="11">Data Empty</td>
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



@endsection
@section('js')

@endsection