<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\ReportTranscation;
use PDF;
use Carbon\Carbon;

class ReportTransactions extends Controller
{
    // Index
	public function index()
	{

		$data = Transactions::all();
		$report = ReportTranscation::all();

		return view('dashboard.transactions.report', compact('data','report'));
	}

	// Process search data
	public function search(Request $request)
	{
		
		if ($request->get('process') == 'search') {
			
			$start_date = $request->start_date.' 00:00:00';
			$end_date = $request->end_date.' 23:59:59';

			$save = ReportTranscation::create([
				'status' => $request->status,
				'start_date' => $start_date,
				'end_date' => $end_date
			]);

			if (!$save) {
				
				return redirect()->back()->with('error', 'Cannot save date!');
			}

			return redirect()->back()->with('success','Successfully save date!');

			//dd($search);
		}
		
	}

	// print pdf 
	public function pdf($id)
	{

		$find = ReportTranscation::findOrFail($id)->first();
		$transaction = Transactions::whereBetween('created_at', [$find->start_date, $find->end_date])->get();
		//dd($transaction);

		$pdf = PDF::loadview('pdf-report', compact('find','transaction'));

		//dd($pdf);

		return $pdf->stream();


	}

}
