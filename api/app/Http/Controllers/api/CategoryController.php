<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\ResponseController as ResponseController;
use App\Category;
use Validator;

class CategoryController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = Category::all();
        return $this->sendResponseData($category, $request);
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
            'type' => 'required|unique:categories'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $input = $request->all();

        $category = Category::create($input);

        if ($category) {
            $id = $category->id;
            $message = "Category created successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Category creation is not successful.";
            return $this->sendError($error, 401);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Int $id)
    {
        $category = Category::where('id', $id)
        ->get();
        return $this->sendResponseData($category, $request);
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
            'type' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $input = $request->all();

        $category = Category::where('id', $id)
        ->update($input);

        if ($category) {
            $message = "Category updated successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Updating category is not successful.";
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
        $category = Category::where('id', $id)
        ->delete();

        if ($category) {
            $message = "Category deleted successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Deleting category is not successful.";
            return $this->sendError($error, 401);
        }
    }
}
