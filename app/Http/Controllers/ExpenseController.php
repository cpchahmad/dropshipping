<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense = new Expense();
        return view('expenses.create')->with('expense', $expense);
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
            'notes' => 'required',
            'price' => 'required',
            'category' => 'required',
            'currency' => 'required',
        ]);

        $expense->title = $request->title;
        $expense->notes = $request->notes;
        $expense->category = $request->category;
        $expense->currency = $request->currency;

        if($request->currency == 'rmb') {
            $expense->price = ((double) $request->price) * 6.6;
        }
        else {
            $expense->price = $request->price;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
