<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\CashIn;
use App\Models\CashOut;
use Carbon\Carbon;

class DashboardControllers extends Controller
{
    //
    public function index()
    {
    	// Filter month
    	$month = Carbon::now();
    	$submonth = Carbon::now()->subMonth();
    	$month_now = Transactions::where('created_at','like', '%'.$month->format('Y-m').'%')->sum('total');
    	$month_before = Transactions::where('created_at', 'like', '%'.$submonth->format('Y-m').'%')->sum('total');

    	// Filter today
    	$day = Carbon::now();
    	$subday = Carbon::now()->subDay();
    	$day_now = Transactions::where('created_at','like','%'.$day->format('Y-m-d').'%')->sum('total');
    	$day_before = Transactions::where('created_at','like','%'.$subday->format('Y-m-d').'%')->sum('total');

    	// Cash In
    	$cash_in = CashIn::where('created_at','like','%'.$day->format('Y-m-d').'%')->first();

    	// Cash Out 
    	$cash_out = CashOut::where('created_at','like','%'.$day->format('Y-m-d').'%')->sum('cash_out');
    	$item_co = CashOut::where('created_at','like','%'.$day->format('Y-m-d').'%')->get();

    	//dd($filter_month);
    	
    	return view('dashboard.dashboard', compact('month_now','month_before','day_now','day_before','cash_in','cash_out','item_co'));
    }

    public function cashin(Request $request)
    {
    	$now = Carbon::now()->format('Y-m-d');
    	$cashin = CashIn::where('created_at', 'like','%'.$now.'%')->first();
    	$cashout = CashOut::where('created_at','like','%'.$now.'%')->sum('cash_out');

    	if ($cashin == null) {
    		
    		CashIn::create([
    			'cash_in' => $request->cash_in
    		]);

    		return redirect()->back()->with('success', 'Successfully add cashin');
    	}
    	else{

    		if($cashout != null)
    		{
    			$update = CashIn::where('created_at','like','%'.$now.'%')->first();
    			$update->update([
    				'cash_in' => $request->cash_in + $cashout
    			]);
    			
    			return redirect()->back()->with('success', 'Successfully update');
    		}

    		$find = CashIn::where('created_at','like','%'.$now.'%')->first();
    		$find->update([
    			'cash_in' => $request->cash_in
    		]);

    		return redirect()->back()->with('success', 'Successfully update');
    	}
    }

    public function cashout(Request $request)
    {
    	
    	$now = Carbon::now()->format('Y-m-d');
    	$total_co = CashOut::where('created_at', 'like','%'.$now.'%')->sum('cash_out');
    	$find_ci = CashIn::where('created_at','like','%'.$now.'%')->first();

    	if($total_co == 0)
    	{
    		$total_ci =  $find_ci->cash_in - $request->cash_out;

    		$find_ci->update([
    			'cash_in' => $total_ci
    		]);

    		CashOut::create([
    		'item' => $request->item,
    		'cash_out' => $request->cash_out
    		]);

    		return redirect()->back()->with('success','Successfully add cash out!');
    	}
    	else
    	{
    		$total_co_final = $total_co + $request->cash_out;
    		if($request->cash_out <= $find_ci->cash_in)
    		{
    			$total_ci =  $find_ci->cash_in - (int)$request->cash_out;

    			//dd(true, 'total = '. (int)$total_co_final, 'total ci = '.$find_ci->cash_in, 'input co = '.$request->cash_out, 'total final ci = '.$total_ci);
    			
    			CashOut::create([
	    		'item' => $request->item,
	    		'cash_out' => $request->cash_out
	    		]);

	    		$find_ci->update([
	    			'cash_in' => $total_ci
	    		]);

	    		return redirect()->back()->with('success','Successfully add cash out!');
	    	}
	    	else{
	    		//dd(null);
	    		return redirect()->back()->with('error', 'Error! Total Cash In is 0');	
	    	}
    	}
    	

    	/*
    	$insert = CashOut::create([
    		'item' => $request->item,
    		'cash_out' => $request->cash_out
    	]);

    	if($insert)
    	{
    		$now = Carbon::now()->format('Y-m-d');
    		$total_co = CashOut::where('created_at', 'like','%'.$now.'%')->sum('cash_out');
    		$find_ci = CashIn::where('created_at','like','%'.$now.'%')->first();

    		if($total_co > $find_ci->cash_in)
    		{
    			$total_ci =  $find_ci->cash_in - $total_co;
	    		$find_ci->update([
	    			'cash_in' => $total_ci
	    		]);

    			return redirect()->back()->with('success','Successfully add cash out!');
    		}
    		else
    		{
    			return redirect()->back()->with('error', 'Error! Cash Out more than Cash In!');
    		}
    		
    	}

    	return redirect()->back()->with('error', 'Cannot add cashout!');
    	
		*/
    }
}
