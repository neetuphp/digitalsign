@extends('layout')
  
@section('content')
<main>
<div class="container">
    <div class="row justify-content-center p-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
  
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
  
                     Welcome {{Auth::user()->name}}
                    <div>
                        <img src="{{ Auth::user()->plain_text }}" alt="User Image">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection