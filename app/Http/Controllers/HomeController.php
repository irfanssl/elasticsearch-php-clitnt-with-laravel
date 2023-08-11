<?php

namespace App\Http\Controllers;
use App\Models\Book;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['book'] = Book::get()->take(1000);
        return view('home', $data);
    }
}
