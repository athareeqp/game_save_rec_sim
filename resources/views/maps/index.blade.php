@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Map</h2>
            </div>
            <div class="pull-right">
                @can('iwak-create')
                <a class="btn btn-success" href="{{ route('maps.create') }}"> Create New Map</a>
                @endcan
                @can('iwak-delete')
                <a class="btn btn-info" href = "/maps/trash">Deleted Data</a>
                @endcan   
            </div>
            <div class="my-3 col-12 col-sm-8 col-md-5">
                <form action="" method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Keyword" name = "keyword" aria-label="Keyword" aria-describedby="basic-addon1">
                        <button class="input-group-text btn btn-primary" id="basic-addon1">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>Map ID</th>
            <th>Save ID</th>
            <th>Score</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($maps as $map)
        <tr>
            <td>{{ $map->map_id }}</td>
            <td>{{ $map->game_id }}</td>
            <td>{{ $map->score }}</td>
            <td>
                <form action="{{ route('maps.destroy',$map->map_id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('maps.show',$map->map_id) }}">Show</a>
                    @can('iwak-edit')
                    <a class="btn btn-primary" href="{{ route('maps.edit',$map->map_id) }}">Edit</a>
                    @endcan
                    @csrf
                    @method('DELETE')
                    @can('iwak-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan             
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $maps->links() !!}
@endsection