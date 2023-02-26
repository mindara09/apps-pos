<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

@inject('carbon', 'Carbon\Carbon')
<center>
	<h3>Report Opname</h3>
	<h6>From : {{ $carbon::parse($find->start_date)->format('l, Y-m-d') }}  to {{ $carbon::parse($find->end_date)->format('l, Y-m-d') }}</h6>
	<br>
</center>

<table class="table table-bordered">
	<tr>
		<th>Name Customer</th>
		<th>Payment</th>
		<th>Status</th>
		<th>Total</th>
	</tr>

	@php 
		$total = 0;
	@endphp
	@foreach ($transaction as $item)
	<tr>
		<td>{{ $item->name_customer }}</td>
		<td>{{ $item->payment}} </td>
		@if( $item->status == 'Print Bill')
		<td class="text-success">{{ $item->status}} </td>
		@else
		<td class="text-danger">{{ $item->status}} </td>
		@endif
		<td>@currency($item->total) </td>
	</tr>
	@php 
		$total += $item->total;
	@endphp
	@endforeach
	<tfoot>
		<th colspan="3">Total</th>
		<th>@currency($total)</th>
	</tfoot>
</table>

<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>