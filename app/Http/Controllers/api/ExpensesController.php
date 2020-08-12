<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\ResponseController as ResponseController;
use App\Expense;
use Validator;

class ExpensesController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expense = Expense::with('categories')->get();
        return $this->sendResponseData($expense, $request);
    }

    /**
     * Display a listing counts per category of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expensesCount(Request $request)
    {
        $expense = Expense::expensesPerCategory()->get();
        return $this->sendResponseData($expense, $request);
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
        $validator = Validator::make($request->all(), [
            'expense_date' => 'required',
            'categories_id' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $input = $request->all();

        $expense = Expense::create($input);

        if ($expense) {
            $id = $expense->id;
            $message = "Expense created successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Expense creation is not successful.";
            return $this->sendError($error, 401);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Int $id)
    {
        $expense = Expense::with('categories')
        ->where('id', $id)
        ->get();
        return $this->sendResponseData($expense, $request);
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
    public function update(Request $request, Int $id)
    {
        $validator = Validator::make($request->all(), [
            'expense_date' => 'required',
            'categories_id' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $input = $request->all();

        $expense = Expense::where('id', $id)
        ->update($input);

        if ($expense) {
            $message = "Expense updated successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Updating expense is not successful.";
            return $this->sendError($error, 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Int $id)
    {
        $expense = Expense::where('id', $id)
        ->delete();

        if ($expense) {
            $message = "Expense deleted successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Deleting expense is not successful.";
            return $this->sendError($error, 401);
        }
    }
}
