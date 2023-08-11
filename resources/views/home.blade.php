@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search" placeholder="type book name to search" aria-label="type book name to search" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="search-btn">Button</button>
            </div>              
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Author</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($book as $boo)
                        <tr>
                            <th>{{$boo->id}}</th>
                            <th>{{$boo->name}}</th>
                            <td>{{$boo->description}}</td>
                            <td>{{$boo->author}}</td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
    </div>
</div>
@endsection
