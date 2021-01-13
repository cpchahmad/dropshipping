<?php

namespace App\Http\Controllers;

use App\Category;
use App\Expense;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::where('shop_id', session()->get('current_shop_domain'))->paginate(20);
        return view('expenses.category')->with('categories', $category);
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
       $this->validate($request, [
           'name' => 'required'
       ]);

       $category = new Category();
       $category->shop_id = session()->get('current_shop_domain');
       $category->category_name = $request->name;
       $category->save();

       return redirect()->back()->with('success', 'Category Added Successfully!');
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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $category = Category::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();
        $category->category_name = $request->name;
        $category->save();

        return redirect()->back()->with('success', 'Category Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Expense::where('shop_id', session()->get('current_shop_domain'))->where('category_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Category cannot be deleted since it is attached to some expense!');
        }
        $category = Category::where('shop_id', session()->get('current_shop_domain'))->where('id', $id)->first();

        $category->delete();

        return redirect()->back()->with('success', 'Category Deleted Successfully!');
    }
}
