<?php
    
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JoinController extends Controller
{
    public function index()
    {
        $joins = DB::table('players')
            ->join('games', 'players.player_id', '=', 'games.player_id')
            ->join('maps', 'games.game_id', '=', 'maps.game_id')
            ->select('players.nickname as nickname', 'games.game_name as save_name', 'maps.score as score')
            ->paginate(10);
            return view('totals.index',compact('joins'))
                ->with('i', (request()->input('page', 1) - 1) * 10);
    }
}