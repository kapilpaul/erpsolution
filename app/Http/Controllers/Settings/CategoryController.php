<?php

namespace App\Http\Controllers\Settings;

use App\Models\Settings\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->is('api/*')) {
            $categories = Category::all();
            return response()->json(['categories' => $categories], 200);
        }
        return view('settings.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }

        try{
            $input = $request->all();
            $input['slug'] = str_slug($request->name, '-');
            $input['name'] = ucwords($request->name);

            Category::create($input);

            return response()->json(['success' => 'Added Successfully!'], 200);

        }catch(PDOException $e){
            return response()->json(['error' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['error' => "QueryException Error!"], 500);
        }
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
        $rules = [
            'name' => 'required',
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }

        try{
            $input = $request->all();
            $input['slug'] = str_slug($request->name, '-');
            $input['name'] = ucwords($request->name);

            $item = Category::findOrFail($id);
            $item->update($input);

            return response()->json(['success' => 'Updated Successfully!'], 200);

        }catch(PDOException $e){
            return response()->json(['error' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['error' => "QueryException Error!"], 500);
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
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => 'Deleted Successfully.'], 200);
    }
}
