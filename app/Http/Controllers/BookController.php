<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\ElasticSearch;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function search(Request $request)
    {
        if($request->query('q')){
            $client = new ElasticSearch();
            return $client->searchDocument( $request->query('q'));
        }


        // Its just native laravel query, I use elasticsearch, so i don't need this
        // $data['book'] = Book::whereFulltext('name', $request->query('q'))
        //                     ->orWhereFulltext('description', $request->query('q'))
        //                     ->orWhereFulltext('author', $request->query('q'))
        //                     ->get();
        // return json_encode($data);
    }
}
