@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
            .container {                
                width: 100vw;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            } 
        </style>
    </head>
    <body>
        <div class="container">
            <div class="justify-content-center">
                <div class="card">
                    <div class="card-header">Welcome!</div>
                    <div class="card-body">
                        <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
