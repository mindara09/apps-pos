@extends('layouts.layout')

@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')
@inject('carbon', 'Carbon\Carbon')
<div class="container-fluid py-4">
	<!-- Notifikasi menggunakan flash session data -->
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
      <div class="row">
      	<!-- Cash In Modal -->
      	<div class="modal fade" id="modal-cashin" tabindex="-1" role="dialog" aria-labelledby="modal-cashin" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="modal-cashin">Cash In</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <form action="{{ route('dashboard.cashin')}}" method="POST">
		      @csrf
		      <div class="modal-body">
		        	<input type="number" name="cash_in" class="form-control">
		      </div>
		      <div class="modal-footer">
		        <button type="submit" class="btn btn-primary">Save changes</button>
		      </div>
		      </form>
		    </div>
		  </div>
		</div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
	        <a data-bs-toggle="modal" data-bs-target="#modal-cashin">
	          <div class="card">
	            <div class="card-body p-3">
	              <div class="row">
	                <div class="col-8">
	                  <div class="numbers">
	                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Cash In</p>
	                    <h5 class="font-weight-bolder mb-0">
	                      @if ($cash_in != null)
	                      	@currency($cash_in->cash_in)
	                      @else
	                      	0
	                      @endif
	                    </h5>
	                  </div>
	                </div>
	                <div class="col-4 text-end">
	                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
	                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>
	        </a>
        </div>
        <!-- Cash Out Modal -->
      	<div class="modal fade" id="modal-cashout" tabindex="-1" role="dialog" aria-labelledby="modal-cashin" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="modal-cashin">Cash Out</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        	<table class="table">
		        		<tr>
		        			<th class="text-sm">Item</th>
		        			<th class="text-sm">Price</th>
		        		</tr>
		        		@forelse($item_co as $item)
		        		<tr>
		        			<td class="text-sm text-dark">{{ $item->item }}</td>
		        			<td class="text-sm text-dark">@currency($item->cash_out)</td>
		        		</tr>
		        		@empty
		        		<center>
			        		<tr>
			        			<td colspan="2" class="text-sm text-center">Cash Out Empty!</td>
			        		</tr>
		        		</center>
		        		@endforelse
		        		<form action="{{ route('dashboard.cashout') }}" method="POST">
		        			@csrf
			        		<tfoot>
			        			<td>
			        				<input type="text" name="item" placeholder="Name Item" class="form-control">
			        			</td>
			        			<td>
			        				<input type="number" name="cash_out" placeholder="Cash Out" class="form-control">
			        			</td>
			        		</tfoot>
		        	</table>
		      </div>
		      <div class="modal-footer">
		        <button type="submit" class="btn btn-primary">Save changes</button>
		      </div>
		      </form>
		    </div>
		  </div>
		</div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        	<a data-bs-toggle="modal" data-bs-target="#modal-cashout">
		      <div class="card">
		        <div class="card-body p-3">
		          <div class="row">
		            <div class="col-8">
		              <div class="numbers">
		                <p class="text-sm mb-0 text-capitalize font-weight-bold">Cash Out</p>
		                <h5 class="font-weight-bolder mb-0">
		                  @if ($cash_out != null)
		                  	@currency($cash_out)
		                  @else
		                  	0
		                  @endif
		                </h5>
		              </div>
		            </div>
		            <div class="col-4 text-end">
		              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
		                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </a>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Order Received of today</p>
                    <h5 class="font-weight-bolder mb-0">
                      @currency($day_now)
                      @if($day_now > $day_before)
                      <span class="text-success text-sm font-weight-bolder">UP</span>
                      @elseif ($day_now < $day_before)
                      <span class="text-danger text-sm font-weight-bolder">DOWN</span>
                      @elseif ($day_now == 0)
                      
                      @endif
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Order Received of {{ $carbon::now()->format('F, Y') }}</p>
                    <h5 class="font-weight-bolder mb-0">
                      @currency($month_now)
                      @if ($month_now > $month_before)
                      <span class="text-success text-sm font-weight-bolder">UP</span>
                      @elseif ($month_now < $month_before)
                      <span class="text-danger text-sm font-weight-bolder">Down</span>
                      @elseif ($month_now != null)

                      @endif
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
      </div>
      <div class="row mt-4">
      	<!-- 
        <div class="col-lg-5 mb-lg-0 mb-4">
          <div class="card z-index-2">
            <div class="card-body p-3">
              <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <h6 class="ms-2 mt-4 mb-0"> Top Products </h6>
              <div class="container border-radius-lg">
                <div class="row">
                  <div class="col-3 py-3 ps-0">
                    <div class="d-flex mb-2">
                      <div class="icon icon-shape icon-xxs shadow border-radius-sm bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center">
                        
                      </div>
                      <p class="text-xs mt-1 mb-0 font-weight-bold">Users</p>
                    </div>
                    <h4 class="font-weight-bolder">36K</h4>
                    <div class="progress w-75">
                      <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    	-->
        <div class="col-lg-12">
          <div class="card z-index-2">
            <div class="card-header pb-0">
              <h6>Sales overview</h6>
              <p class="text-sm">
                <!-- <i class="fa fa-arrow-up text-success"></i> -->
                From <span class="font-weight-bold"><u>{{ $month_sales[1]['name'] }}</u></span> to <span class="font-weight-bold"><u>{{ $month_sales[5]['name']}}</u></span> 
              </p>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
@endsection
@section('js')
<script>


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: [
        "{{ $month_sales[7]['name']}}",
        "{{ $month_sales[6]['name']}}",
        "{{ $month_sales[5]['name']}}",
        "{{ $month_sales[4]['name']}}",
        "{{ $month_sales[3]['name']}}",
        "{{ $month_sales[2]['name']}}",
        "{{ $month_sales[1]['name']}}",

        ],
        datasets: [{
            label: "Total Order Received",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [
            @php echo $month_sales[7]['total'] @endphp,
            @php echo $month_sales[6]['total'] @endphp,
            @php echo $month_sales[5]['total'] @endphp,
            @php echo $month_sales[4]['total'] @endphp,
            @php echo $month_sales[3]['total'] @endphp,
            @php echo $month_sales[2]['total'] @endphp,
            @php echo $month_sales[1]['total'] @endphp,
            ],
            maxBarThickness: 6

          }
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
@endsection