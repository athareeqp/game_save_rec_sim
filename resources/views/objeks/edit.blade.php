@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Object</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('objeks.index') }}"> Back</a>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('objeks.update',$objek->objek_id) }}" method="POST">
        @csrf
        @method('PUT')
         <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Object ID:</strong>
                    <input type="number" name="objek_id" value="{{ $objek->objek_id }}"class="form-control" placeholder="Object ID">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Map ID:</strong>
                    <input type="text" name="map_id" value="{{ $objek->map_id }}" class="form-control" placeholder="Map ID">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Position:</strong>
                    <input type="text" name="position" value="{{ $objek->position }}"class="form-control" placeholder="Position">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection