@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Save</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('maps.index') }}"> Back</a>
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
    <form action="{{ route('maps.update',$map->map_id) }}" method="POST">
        @csrf
        @method('PUT')
         <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Map ID:</strong>
                    <input type="number" name="map_id" value="{{ $map->map_id }}" class="form-control" placeholder="Map ID">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Save ID:</strong>
                    <input type="text" name="game_id" value="{{ $map->game_id }}" class="form-control" placeholder="Save ID">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Score:</strong>
                    <input type="text" name="score" value="{{ $map->score }}" class="form-control" placeholder="Score">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection