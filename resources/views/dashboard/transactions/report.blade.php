@extends('layouts.layout')

@section('title','Report Transactions')
@section('page-title','Report Transactions')

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
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Filter Date</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <form method="POST" action="{{ route('report.search', 'process=search')}}">
            @csrf
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <td>
                    <label>From Date</label>
                    <input type="date" name="start_date" class="form-control">
                  </td>
                  <td>
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control">
                  </td>
                  <td>
                    <label>Status</label>
                    <select class="form-control" name="status">
                      <option selected>Options Status</option>
                      <option value="All">All</option>
                      <option value="Print Bill">Print Bill</option>
                      <option value="Open Bill">Open Bill</option>
                    </select>
                  </td>
                  <td>
                    <button type="submit" class="mt-5 btn btn-sm btn-primary">Submit</button>
                  </td>
                </tr>
              </thead>
            </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Report table</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start Date</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End Date</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Option</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  @inject('carbon', 'Carbon\Carbon')
                  @forelse($report as $item)
                  <td class="align-middle text-center text-sm">
                    @if($item->status == 'Print Bill')
                    <span class="badge badge-sm bg-gradient-success">{{ $item->status}}</span>
                    @else
                    <span class="badge badge-sm bg-gradient-danger">{{ $item->status}}</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-dark text-xs font-weight-bold">{{ $carbon::parse($item->start_date)->format('l, Y-m-d') }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-dark text-xs font-weight-bold">{{ $carbon::parse($item->end_date)->format('l, Y-m-d') }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('report.pdf', $item->id)}}" class="badge badge-sm bg-gradient-primary">Print PDF</a>
                  </td>
                </tr>
                @empty
                <center>
                  <tr>
                    <td colspan="6" class="text-center">Data empty!</td>
                  </tr>
                </center>
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