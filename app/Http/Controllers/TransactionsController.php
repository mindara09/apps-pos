<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\LogController;
use App\Models\Transactions;
use App\Models\StockItems;
use App\Models\CategoryItems;
use Carbon\Carbon;

class TransactionsController extends Controller
{
    // get all data transaction
    public function index()
    {

    	$items = Transactions::all()->sortByDesc('created_at');
        $stock_items = StockItems::all();

    	return view('dashboard.transactions.index', compact('items', 'stock_items'));
    }

    // create page transactions
    public function new_transactions()
    {

    	$cartItems = session()->get('orders');

    	$items = StockItems::all();
    	$cat = CategoryItems::all();

    	// dd($cartItems);
    	return view('dashboard.transactions.new_transactions', compact('items','cat','cartItems'));
    }

    public function submit_order(Request $request)
    {
        // Submit Order
        if($request->get('process') == "submit_order")
        {
            if($request->status == 'Open Bill')
            {
                $submit_order = session()->get('submit_order');
                $customer = session()->get('customer');
                $order = session()->get('orders');
                $total = session()->get('total');


                $store = Transactions::create([
                    'user_id' => auth()->user()->id,
                    'name_customer' => $customer['name_customer'],
                    'code_transaction' => rand()."-TRANSAKSI",
                    'orders' => json_encode($order),
                    'payment' => 'null',
                    'notes' => $customer['notes'],
                    'status' => 'Open Bill',
                    'total' => $request->total,
                    'change' => '0',
                    'cash_customer' =>'0'
                ]);

                session()->forget('submit_order');
                session()->forget('customer');
                session()->forget('orders');
                session()->forget('total');

                return redirect('/transactions')->with('success', "Transaction submitted at sessions");
            }
            else 
            {
                $total = session()->get('total');

                
                if($request->status == 'Print Bill' && $request->cash_customer != null)
                {
                    $submit_order = session()->get('submit_order');
                    $customer = session()->get('customer');
                    $order = session()->get('orders');
                    $total = session()->get('total');

                    if($total == null)
                    {
                        //dd($total);
                        return redirect('/transactions/new')->with('error', "Dont input status first while calculate total");
                    }

                    $store = Transactions::create([
                        'user_id' => auth()->user()->id,
                        'name_customer' => $customer['name_customer'],
                        'code_transaction' => rand()."-TRANSAKSI",
                        'orders' => json_encode($order),
                        'payment' => $request->payment,
                        'notes' => $customer['notes'],
                        'status' => 'Print Bill',
                        'total' => $total['total_order']['total'],
                        'change' => $total['total_order']['change'],
                        'cash_customer' => $total['total_order']['cash_customer']
                    ]);

                    session()->forget('submit_order');
                    session()->forget('customer');
                    session()->forget('orders');
                    session()->forget('total');

                    return redirect('/transactions')->with('success', "Transaction submitted at database");
                }


                elseif ($request->cash_customer != null) 
                {
                    $totalOrder = session()->get('total');

                    if ($request->cash_customer <= $request->total) {
                        return redirect('/transactions/new')->with('error', "Cash customer minimimum more than total");
                    }
                    $change = $request->cash_customer - $request->total;
                    $totalOrder = [
                        "total_order" => [
                            "total" => $request->total,
                            "cash_customer" => $request->cash_customer,
                            "change" => $change
                        ]
                    ];

                    session()->put("total", $totalOrder);

                    //dd(session()->get('total'));
                    return redirect('/transactions/new')->with('success', "Total submitted at sessions");
                }
            }
            
        }

    }
    // process transactions
    public function process_transactions(Request $request)
    {

        if($request->get('process') == 'submit_customer')
        {
            //dd($request->all());
            $customer = session()->get('customer');
            $customer = [
                'name_customer' => $request->name_customer,
                'notes' => $request->notes
            ];

            session()->put("customer", $customer);

            // dd(session()->get('customer'));

            return redirect('/transactions/new')->with('success', "Customer submitted at sessions");
        }

        if ($request->get('process') == 'submit_total') {
            //dd($request->all());
            $totalOrder = session()->get('total');

            if ($request->cash_customer <= $request->total) {
                return redirect('/transactions/new')->with('error', "Cash customer minimimum more than total");
            }
            $change = $request->cash_customer - $request->total;
            $totalOrder = [
                "total_order" => [
                    "total" => $request->total,
                    "cash_customer" => $request->cash_customer,
                    "change" => $change
                ]
            ];

            session()->put("total", $totalOrder);

            //dd(session()->get('total'));
            return redirect('/transactions/new')->with('success', "Total submitted at sessions");
        }

    	$find_order = StockItems::where('id', $request->order_item)->first();
        //$find_order = StockItems::find($request->order)->first(); 

    	if(!$find_order)
    	{
    		return redirect('/transactions/new')->with('error', 'Order item data empty!');
    	}
        
    	$cart = session()->get('orders');

    	// if cart is empty then this the first product

    	if (!$cart) {
    		
            // dd($find_order);
    		$cart = [
    			$request->order_item => [
    				"order_id" => $find_order->id,
    				"qty" => $request->quantity,
    				"price" => $find_order->price * $request->quantity
    			]
    		];

    		session()->put("orders", $cart);
    	}

    	if(isset($cart[$request->order_item]))
    	{
    		$cart[$request->order_item]['qty']++;
            $cart[$request->order_item]['price'] = $cart[$request->order_item]['qty'] * $find_order->price;

    		session()->put('orders', $cart);

    		
    	}

        $cart = session()->get('orders');
    	// if item not exist in cart then add to cart with quantity = 1
        $cart[$request->order_item] = [
            "order_id" => $find_order->id,
			"qty" => $request->quantity,
			"price" => $find_order->price * $request->quantity
        ];

        session()->put('orders', $cart);

    	return redirect('/transactions/new');

    }

    public function update_transactions(Request $request, $id)
    {
        $find_order = StockItems::where('id', $request->order_item)->first();
        
        if($request->order_item and $request->quantity)
        {
            $cart = session()->get('orders');
            $cart[$request->order_item]["qty"] = $request->quantity;
            $cart[$request->order_item]['price'] = $cart[$request->order_item]['qty'] * $find_order->price;
            session()->put('orders', $cart);
            return redirect('/transactions/new')->with('success', 'Cart updated successfully');
        }
    }

    public function delete_transactions($id)
    {
        if($id) {
            $cart = session()->get('orders');

            //dd($cart);

            if(isset($cart[$id])) {

                unset($cart[$id]);
                session()->put('orders', $cart);
                //dd(session()->get('orders'));

            }
            return redirect('/transactions/new')->with('success', 'Product removed successfully');
        }
    }

    public function edit_orders($id)
    {
        $item = Transactions::where('id', $id)->first();
        $stock_items = StockItems::all();
        $category = CategoryItems::all();

        return view('dashboard.transactions.edit_transactions', compact('item', 'stock_items'));
        
    }

    public function process_edit_orders(Request $request)
    {
        
        $update = Transactions::findOrFail($request->id);
        //dd($request->all());
        if ($request->cash_customer <= $request->total) {
            return redirect()->back()->with('error', "Cash customer minimimum more than total");
        }

        if ($request->payment == 'Choose payment') {
            return redirect()->back()->with('error', "Input payment cannot empty");
        }

        $change = $request->cash_customer - $request->total;
        $update->update([
            'cash_customer' => $request->cash_customer,
            'change' => $change,
            'payment' => $request->payment,
            'status' => 'Print Bill'
        ]);

        return redirect()->back()->with('success', "Total submitted at database");
    }

    public function delete_orders($id)
    {
        // delte /transactions
        $delete_item = Transactions::find($id);
        $delete_item->delete();

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has delete orders');

        return redirect('/transactions')->with(['success' => 'Order has been deleted successfully']);
    }

}
