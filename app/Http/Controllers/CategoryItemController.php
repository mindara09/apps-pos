<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryItems;
use App\Http\Controllers\LogController;

class CategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data all category item
        $category_items = CategoryItems::all();
        return view('dashboard.category-items.index', compact('category_items'));
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
        // store data category items
        $this->validate($request, [
            'name_category' => 'required'
        ]);   
        $post = CategoryItems::create([
            'name_category' => $request->name_category
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has add category');

        if ($post) {
            return redirect()
                ->route('category.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('category.index')
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
        // update data category
        $this->validate($request, [
            'name_category' => 'required'
        ]);

        $post = CategoryItems::findOrFail($id);
        
        $post->update([
            'name_category' => $request->name_category
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update category');

        if ($post) {
            return redirect()
                ->route('category.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('category.index')
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
        // delete category by id

        $delete = CategoryItems::find($id);

        $delete->delete();

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has delete category');

        return redirect('/category-item');
    }
}
