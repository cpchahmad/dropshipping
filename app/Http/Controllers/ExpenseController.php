<?php

namespace App\Http\Controllers;

use App\Category;
use App\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('expenses.index')->with('expenses', Expense::paginate(20));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Category::count() <= 0) {
            return redirect(route('categories.index'))->with('error', 'Expense cannot be created since no category exists!');
        }

        return view('expenses.create')->with('categories', Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $expense = new Expense();

        $this->validate($request, [
            'title' => 'required',
            'price' => 'required',
            'category' => 'required',
            'currency' => 'required',
        ]);

        $expense->title = $request->title;
        $expense->notes = $request->notes;
        $expense->category_id = $request->category;
        $expense->currency = $request->currency;

        if($request->currency == 'rmb') {
            $expense->rmb_price = ((double) $request->price);
            $expense->usd_price = $request->price / 6.6;
        }
        else {
            $expense->usd_price = $request->price;
        }

        $expense->save();

        return redirect(route('expenses.index'))->with('success', 'Expense Created sucessfully!');

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
        $expense = Expense::find($id);
        return view('expenses.create')->with('expense', $expense)->with('categories', Category::all());
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
        $expense = Expense::find($id);

        $this->validate($request, [
            'title' => 'required',
            'price' => 'required',
            'category' => 'required',
            'currency' => 'required',
        ]);

        $expense->title = $request->title;
        $expense->notes = $request->notes;
        $expense->category_id = $request->category;
        $expense->currency = $request->currency;

        if($request->currency == 'rmb') {
            $expense->rmb_price = ((double) $request->price) * 6.6;
            $expense->usd_price = $request->price;
        }
        else {
            $expense->usd_price = $request->price;
        }

        $expense->save();

        return redirect(route('expenses.index'))->with('success', 'Expense Updated sucessfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        return redirect(route('expenses.index'))->with('success', 'Expense Deleted sucessfully!');

    }
}
