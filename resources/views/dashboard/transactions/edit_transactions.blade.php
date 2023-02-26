@extends('layouts.layout')

@section('title','Edit Transactions')
@section('page-title','Edit Transactions')

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
      <div class="card mt-0 mb-3">
        <div class="card-header pb-0 p-3">
          <div class="row">
            <div class="col-6 d-flex align-items-center">
              <h6 class="mb-0">Customer</h6>
            </div>
            <div class="col-6 text-end">
              @if ($item->status == 'Open Bill')
              <button type="submit" class="btn btn-sm bg-gradient-danger mb-0">
                {{ $item->status }}
              </button>
              @else
              <button type="submit" class="btn btn-sm bg-gradient-success mb-0">
                {{ $item->status }}
              </button>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body p-3">
          <div class="row">
            <div class="col-md-6 mb-md-0 mb-4">
              <label id="name_customer">Name Customer</label>
                <input type="text" name="name_customer" class="form-control" value="{{ $item->name_customer }}">
            </div>
            <div class="col-md-6">
              <label id="notes">Notes</label>
               <input type="text" name="notes" class="form-control" value="{{ $item->notes }}">
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header pb-0 px-3">
          <div class="float-start">
            <h6 class="mb-0">New Transactions</h6>
          </div>
        </div>
        <div class="card-body pt-4 p-3">
          <ul class="list-group">
            @foreach (json_decode($item->orders) as $x => $val)
            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
              <div class="d-flex flex-column">
                <h6 class="mb-3 text-sm">{{ $stock_items->where('id', $x)->first()->name_item }}</h6>
                <span class="mb-2 text-xs">Quantity: <span class="text-dark ms-sm-2 font-weight-bold">{{ $val->qty}}</span></span>
                <span class="text-xs">Price: <span class="text-dark ms-sm-2 font-weight-bold">@currency($val->price)</span></span>
              </div>
            </li>
            @endforeach
            <li class="list-group-item border-0 d-flex p-4 mb-2 border-radius-lg">
              <div class="d-flex flex-column">
                <h5>Total :</h5>
              </div>
              <input type="number" hidden="" name="total" value="">
              <div class="ms-auto text-end">
                <h5>@currency($item->total)</h5>
              </div>
            </li>
          </ul>
        </div>
      </div>
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
              <form action="{{ route('transaction.process.edit_orders')}}" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{ $item->id }}">
              <input type="hidden" name="total" value="{{ $item->total }}">
              <table class="table mb-0 mt-3">
                  <tbody>
                    @if($item->status == 'Open Bill')
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">Status</th>
                      <td>
                        <select class="form-select mb-2" style="border:none; border-bottom: 1px solid; font-size: 16px; text-align: center;" name="status">
                          <option value="Print Bill" selected>Print Bill</option>
                        </select>
                      </td>
                      <th>
                      </th>
                    </tr>
                    @endif
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">
                        Cash Customer
                      </th>
                      <td>
                        <center>
                          <div class="mb-3">
                            @if($item->status == "Print Bill")
                                @currency($item->cash_customer)
                            @else 
                              <input type="number" style="border:none; border-bottom: 1px solid" class="form-control" id="cash_customer" name="cash_customer">
                            @endif
                          </div>
                        </center>
                      </td>
                      @if($item->status == "Open Bill")
                      <th>
                        <button type="submit" class="btn btn-sm btn-warning" style="margin-left: 22px;">C</button>
                      </th>
                      @endif
                    </tr>
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">Change</th>
                      @if($item->change != 0)
                      <input type="hidden" name="change" value="{{ $item->change }}">
                      <td>
                        <center>
                          @currency($item->total)
                        </center>
                      </td>
                      @else
                      <td>
                        <input type="hidden" name="change" value="{{ $item->change }}">
                        <center>
                          @currency($item->change)
                        </center>
                      </td>
                      @endif
                    </tr>
                    <tr>
                      <th colspan="4" class="font-weight-bold mb-0">Payment</th>
                      @if($item->status == "Open Bill")
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
                      @else
                      <td colspan="4">
                        <center>
                          {{ $item->payment}}
                        </center>
                      </td>
                      @endif
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

@endsection
@section('js')

@endsection