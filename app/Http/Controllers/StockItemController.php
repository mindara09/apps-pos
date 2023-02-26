<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockItems;
use App\Models\CategoryItems;
use App\Http\Controllers\LogController;

class StockItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // filter by category order
        $filter = $request->get('category_order');
        if ($filter) {
            $stock_items = StockItems::where('category_order', $filter)->get();
            $category_items = CategoryItems::all();
            return view('dashboard.stock-items.index', compact('stock_items', 'category_items'));
        }

        // get all data stock items
        $stock_items = StockItems::all();
        $category_items = CategoryItems::all();

        // dd($stockItems);
        return view('dashboard.stock-items.index', compact('stock_items', 'category_items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store data stock item
         $this->validate($request, [
            'name_item' => 'required',
            'category_item' => 'required',
            'category_order' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        $post = StockItems::create([
            'code_item' => "ITCD".rand(1000,2000),
            'name_item' => $request->name_item,
            'category_item' => $request->category_item,
            'category_order' => $request->category_order,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has add stock item');

        if ($post) {
            return redirect()
                ->route('stock.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('stock.index')
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // update data stock item
        $this->validate($request, [
            'name_item' => 'required',
            'category_item' => 'required',
            'category_order' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        $post = StockItems::findOrFail($id);
        $post->update([
            'code_item' => $post->code_item,
            'name_item' => $request->name_item,
            'category_item' => $request->category_item,
            'category_order' => $request->category_order,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update stock item');

        if ($post) {
            return redirect()
                ->route('stock.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('stock.index')
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delte stock item
        $delete_item = StockItems::find($id);
        $delete_item->delete();

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has delete stock item');

        return redirect('/stock-item');
    }
}
