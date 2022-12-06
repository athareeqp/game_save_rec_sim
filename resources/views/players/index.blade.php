@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Player</h2>
            </div>
            <div class="pull-right">
                @can('iwak-create')
                <a class="btn btn-success" href="{{ route('players.create') }}"> Create New Player</a>
                @endcan
                @can('iwak-delete')
                <a class="btn btn-info" href = "/players/trash">Deleted Data</a>
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
            <th>ID Player</th>
            <th>Player Nickname</th>
            <th>Email</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($players as $player)
        <tr>
            <td>{{ $player->player_id }}</td>
            <td>{{ $player->nickname }}</td>
            <td>{{ $player->email }}</td>
            <td>
                <form action="{{ route('players.destroy',$player->player_id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('players.show',$player->player_id) }}">Show</a>
                    @can('iwak-edit')
                    <a class="btn btn-primary" href="{{ route('players.edit',$player->player_id) }}">Edit</a>
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
    {!! $players->links() !!}
@endsection