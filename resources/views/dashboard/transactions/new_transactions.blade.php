@extends('layouts.layout')

@section('title','New Transactions')
@section('page-title','New Transactions')

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

<!-- Table Data -->
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-7 mt-4">
      <form action="{{ route('transaction.process_transactions','process=submit_customer')}}" method="POST">
      @csrf
      <div class="card mt-0 mb-3">
        <div class="card-header pb-0 p-3">
          <div class="row">
            <div class="col-6 d-flex align-items-center">
              <h6 class="mb-0">Customer</h6>
            </div>
            <div class="col-6 text-end">
              <button type="submit" class="btn btn-sm bg-gradient-dark mb-0">
                <i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Submit
              </button>
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          <div class="row">
            <div class="col-md-6 mb-md-0 mb-4">
              <label id="name_customer">Name Customer</label>
              @if (session()->get('customer'))
                @php $customer = session()->get('customer'); @endphp
                <input type="text" name="name_customer" class="form-control" value="{{ $customer['name_customer']}}">
              @else
                <input type="text" name="name_customer" class="form-control">
              @endif
            </div>
            <div class="col-md-6">
              <label id="notes">Notes</label>
              @if (session()->get('customer'))
                @php $customer = session()->get('customer'); @endphp
                <input type="text" name="notes" class="form-control" value="{{ $customer['notes']}}">
              @else
               <input type="text" name="notes" class="form-control">
              @endif
            </div>
          </div>
        </div>
      </div>
      </form>
      <div class="card">
        <div class="card-header pb-0 px-3">
          <div class="float-start">
            <h6 class="mb-0">New Transactions</h6>
          </div>
          <a href="#"  data-bs-toggle="modal" data-bs-target="#addOrders" class="float-end btn btn-sm btn-primary">Add Orders</a>
          <div class="modal fade" id="addOrders" tabindex="-1" aria-labelledby="addOrders" aria-hidden="true">
            <div class="modal-dialog modal-md vertical-align-center">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('transaction.process_transactions')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <label for="orderItems" class="form-label">Order Item</label>
                      <select class="form-select @error('order_item') is-invalid @enderror" name="order_item">
                        <option selected>Open this select menu</option>
                        @forelse ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name_item }} - {{ $cat->where('id', $item->category_item)->first()->name_category }}</option>
                        @empty
                        <option>Data Empty</option>
                        @endforelse
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="quantity" class="form-label">Quantity</label>
                      <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="1">
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
        <div class="card-body pt-4 p-3">
          <ul class="list-group">
            @php
                $total = 0;
            @endphp
            @if ($cartItems != null)
            @foreach ($cartItems as $data)
            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
              <div class="d-flex flex-column">
                <h6 class="mb-3 text-sm">{{ $items->where('id',$data['order_id'])->first()->name_item }}m</h6>
                <span class="mb-2 text-xs">Category Item: <span class="text-dark font-weight-bold ms-sm-2">{{ $cat->where('id',$items->where('id',$data['order_id'])->first()->category_item)->first()->name_category }}</span></span>
                <span class="mb-2 text-xs">Quantity: <span class="text-dark ms-sm-2 font-weight-bold">{{ $data['qty'] }}</span></span>
                <span class="text-xs">Price: <span class="text-dark ms-sm-2 font-weight-bold">@currency($data['price'])</span></span>
              </div>
              <div class="ms-auto text-end">
                <a class="btn btn-link text-dark mb-0" data-bs-toggle="modal" data-bs-target="#edit-{{ $data['order_id'] }}" ><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                <form onsubmit="return confirm('Are your sure?');" action="{{ route('transaction.delete_transactions', $data['order_id'])}}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-link text-danger text-gradient mb-0" style="border: none;"><i class="far fa-trash-alt me-2"></i>Delete</button>
                </form>
              </div>
            </li>
            @php
                $total += $data['price']
            @endphp
            @endforeach
            @endif
            <li class="list-group-item border-0 d-flex p-4 mb-2 border-radius-lg">
              <div class="d-flex flex-column">
                <h5>Total :</h5>
              </div>
              <input type="number" hidden="" name="total" value="{{$total}}">
              <div class="ms-auto text-end">
                <h5>@currency($total)</h5>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <!--
      <div class="card mb-4">
        <div class="card-header pb-0 mb-3">
          <div class="float-start">
          	<h5>New Transactions</h5>
          </div>
          <a href="#"  data-bs-toggle="modal" data-bs-target="#addOrders" class="float-end btn btn-sm btn-primary">Add Orders</a>
          <div class="modal fade" id="addOrders" tabindex="-1" aria-labelledby="addOrders" aria-hidden="true">
            <div class="modal-dialog modal-md vertical-align-center">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('transaction.process_transactions')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <label for="orderItems" class="form-label">Order Item</label>
                      <select class="form-select @error('order_item') is-invalid @enderror" name="order_item">
                        <option selected>Open this select menu</option>
                        @forelse ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name_item }} - {{ $cat->where('id', $item->category_item)->first()->name_category }}</option>
                        @empty
                        <option>Data Empty</option>
                        @endforelse
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="quantity" class="form-label">Quantity</label>
                      <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="1">
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
            <div class="container float-start">
              <form action="{{ route('transaction.new_transactions')}}" method="POST">
                @csrf
                <div class="row">
                  <div class="mb-3 col">
                    <label for="username" class="form-label">Name Customer</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username">
                  </div>
                  <div class="mb-3 col">
                    <label for="username" class="form-label">Name Customer</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username">
                  </div>
                  <div class="mb-3 col">
                    <label for="username" class="form-label">Notes</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username">
                  </div>
                </div>
              </form>
            </div>
            <table class="table mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Code Items</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name Items</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category Items</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Options</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $total = 0;
                @endphp
                @if ($cartItems != null)
                @foreach ($cartItems as $data)
                <tr>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $items->where('id',$data['order_id'])->first()->code_item }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $items->where('id',$data['order_id'])->first()->name_item }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $cat->where('id',$items->where('id',$data['order_id'])->first()->category_item)->first()->name_category }}</p>
                  </td>
                  <td>
                    <center>
                      <p class="text-xs font-weight-bold mb-0">{{ $data['qty'] }}</p>
                    </center>
                  </td>
                  <td>
                    <center>
                      <p class="text-xs font-weight-bold mb-0">@currency($data['price'])</p>
                    </center>
                  </td>
                  <td class="align-middle">
                    <center>
                      <a class="btn btn-link text-dark px-3 mb-0" data-bs-toggle="modal" data-bs-target="#edit-{{ $data['order_id'] }}" ><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                      <form onsubmit="return confirm('Are your sure?');" action="{{ route('transaction.delete_transactions', $data['order_id'])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0" style="border: none;"><i class="far fa-trash-alt me-2"></i>Delete</button>
                      </form>
                    </center>
                  </td>
                </tr>
                @php
                    $total += $data['price']
                @endphp
                @endforeach
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="font-weight-bold">Total</th>
                  <td>
                    <center>
                      <input type="number" hidden="" name="total" value="{{$total}}">
                      @currency($total)
                    </center>
                  </td>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      -->
    </div>
    <div class="col-md-5 mt-4">
      <div class="card mb-4">
        <div class="card-header pb-0 px-3">
          <div class="row">
            <div class="col-md-6">
              <h6 class="mb-0">Payment</h6>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <div class="container float-start">
              <form action="{{ route('transaction.submit_order', 'process=submit_order')}}" method="POST">
              @csrf
              <table class="table mb-0 mt-3">
                  <tbody>
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">Status</th>
                      <td>
                        <select class="form-select mb-2" style="border:none; border-bottom: 1px solid; font-size: 16px; text-align: center;" name="status">
                          <option selected>Open this select menu</option>
                          <option value="Open Bill">Open Bill</option>
                          <option value="Print Bill">Print Bill</option>
                        </select>
                      </td>
                      <th>
                      </th>
                    </tr>
                    <input type="number" hidden="" name="total" value="{{$total}}">
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">
                        Cash Customer
                      </th>
                      <td>
                        <center>
                          <div class="mb-3">
                            @if(session()->get('total'))
                              @php 
                                $cash_customer = session()->get('total');
                              @endphp
                              <input type="number" style="border:none; border-bottom: 1px solid; font-size: 16px; text-align: center; margin-left: 22px;" class="form-control" id="cash_customer" name="cash_customer" value="{{ $cash_customer['total_order']['cash_customer']}}">
                            @else 
                              <input type="number" style="border:none; border-bottom: 1px solid" class="form-control" id="cash_customer" name="cash_customer">
                            @endif
                          </div>
                        </center>
                      </td>
                      <th>
                        <button type="submit" class="btn btn-sm btn-warning" style="margin-left: 22px;">C</button>
                      </th>
                    </tr>
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">Change</th>
                      <td>
                        <center>
                          @if(session()->get('total'))
                            @php 
                              $total = session()->get('total');
                            @endphp
                             @currency($total['total_order']['change'])
                          @endif
                        </center>
                      </td>
                      <th>
                      </th>
                    </tr>
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">Payment</th>
                      <td>
                        <select class="form-select mb-2" style="border:none; border-bottom: 1px solid; font-size: 16px; text-align: center;" name="payment">
                          <option selected>Choose payment</option>
                          <option value="Shopee">Shopee</option>
                          <option value="Gojek">Gojek</option>
                          <option value="Grab">Grab</option>
                          <option value="Cash">Cash</option>
                          <option value="QRIS">QRIS</option>
                          <option value="ATM Transfer">ATM Transfer</option>
                        </select>
                      </td>
                      <th>
                      </th>
                    </tr>
                  </tbody>
              </table>
              <button type="submit" class="btn btn-info col-5 mt-4 float-end" name='submit'>Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@if ($cartItems)
@foreach($cartItems as $data)
<!-- Modal -->
<div class="modal fade" id="edit-{{ $data['order_id']}}" tabindex="-1" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog modal-md vertical-align-center">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('transaction.update_transactions', $data['order_id'])}}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="orderItems" class="form-label">Order Item</label>
            <select class="form-select @error('order_item') is-invalid @enderror" name="order_item">
              <option>Open this select menu</option>
              @forelse ($items as $item)
              <option value="{{ $item->id }}" {{ $item->id == $data['order_id'] ? 'selected' : ''}}>{{ $item->name_item }} - {{ $cat->where('id', $item->category_item)->first()->name_category }}</option>
              @empty
              <option>Data Empty</option>
              @endforelse
            </select>
          </div>
          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ $data['qty']}}">
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
@endif


@endsection
@section('js')

@endsection