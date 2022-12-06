@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Object</h2>
            </div>
            <div class="pull-right">
                @can('iwak-create')
                <a class="btn btn-success" href="{{ route('objeks.create') }}"> Create New Object</a>
                @endcan
                @can('iwak-delete')
                <a class="btn btn-info" href = "/objeks/trash">Deleted Data</a>
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
            <th>Object ID</th>
            <th>Map ID</th>
            <th>Position</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($objeks as $objek)
        <tr>
            <td>{{ $objek->objek_id }}</td>
            <td>{{ $objek->map_id }}</td>
            <td>{{ $objek->position }}</td>
            <td>
                <form action="{{ route('objeks.destroy',$objek->objek_id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('objeks.show',$objek->objek_id) }}">Show</a>
                    @can('iwak-edit')
                    <a class="btn btn-primary" href="{{ route('objeks.edit',$objek->objek_id) }}">Edit</a>
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
    {!! $objeks->links() !!}
@endsection