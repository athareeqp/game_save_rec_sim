@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>List View Join</h2>
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
            <th>Player</th>
            <th>Save Name</th>
            <th>Score</th>
        </tr>
        @foreach ($joins as $join)
        <tr>
            <td>{{ $join->nickname }}</td>
            <td>{{ $join->save_name }}</td>
            <td>{{ $join->score }}</td>
        </tr>
        @endforeach
    </table>
    {!! $joins->links() !!}
@endsection