@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"
                style="text-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">
                <a href="{{ route('price.index') }}">Price</a> / Input Currency </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card"
                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">
                    <div class="card-body">
                        <form action="{{ route('price.update-currency') }}" enctype="multipart/form-data" method="POST"
                            id="importPrice" class="forms-sample">
                            @csrf
                            @foreach ($currencyData as $currency)
                                <div class="form-group">
                                    <label for="input{{ $currency }}">{{ $currency }}</label>
                                    <input type="text" class="form-control currency-input" id="input{{ $currency }}"
                                        name="currencies[{{ $currency }}]" placeholder="Enter {{ $currency }}">
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                            <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
