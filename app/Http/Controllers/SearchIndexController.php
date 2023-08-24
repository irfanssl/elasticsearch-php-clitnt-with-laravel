<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ElasticsearchController;

class SearchIndexController extends Controller
{
    public function searchBooks(Request $request){
        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->messages()]);
        }
        $search = new ElasticsearchController('books_dataset'); // name of the index you want to search
        return $search->searchDocument($request->keyword, ['name','description','author']); // specify which column you want to search
    }
}
