<?php
    
namespace App\Http\Controllers;
    
use App\Models\Game;
use Illuminate\Http\Request;
use DB;
    
class GameController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:iwak-list|iwak-create|iwak-edit|iwak-delete', ['only' => ['index','show']]);
         $this->middleware('permission:iwak-create', ['only' => ['create','store']]);
         $this->middleware('permission:iwak-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:iwak-delete', ['only' => ['destroy','deletelist']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $keyword = $request->keyword;
        // $players = player::where('nama_player','LIKE','%'.$keyword.'%')
        //             ->paginate(10);
        // return view('players.index',compact('players'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);
        $keyword = $request->keyword;
        $games = DB::table('games')->where('player_id','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('games.index',compact('games'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('games.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // request()->validate([
        //     'supplierid' => 'required',
        //     'suppliername' => 'required',
        //     'sphone' => 'required',
        //     'slocation' => 'required',
        // ]);
    
        // Supplier::create($request->all());
    
        // return redirect()->route('players.index')
        //                 ->with('success','Supplier created successfully.');
        $request->validate([
            'game_id' => 'required',
            'player_id' => 'required',
            'game_name' => 'required',
        ]);
        
        DB::insert('INSERT INTO games(game_id, player_id, game_name) VALUES (:game_id, :player_id, :game_name)',
        [
            'game_id' => $request->game_id,
            'player_id' => $request->player_id,
            'game_name' => $request->game_name,
        ]
        );

        return redirect()->route('players.index')->with('success', 'Save added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(game $game)
    {
        return view('games.show',compact('game'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function edit(Supplier $supplier)
    // {
    //     return view('players.edit',compact('supplier'));
    // }
    public function edit($id)
    {
        $game = DB::table('games')->where('game_id', $id)->first();
        return view('games.edit',compact('game'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Supplier $supplier)
    // {
    //      request()->validate([
    //         'supplierid' => 'required',
    //         'suppliername' => 'required',
    //         'sphone' => 'required',
    //         'slocation' => 'required',
    //     ]);
    
    //     $supplier->update($request->all());
    
    //     return redirect()->route('players.index')
    //                     ->with('success','Supplier updated successfully');
    // }
    public function update($id, Request $request) {
        $request->validate([
            'game_id' => 'required',
            'player_id' => 'required',
            'game_name' => 'required',
        ]);

        DB::update('UPDATE games SET game_id = :game_id, player_id = :player_id, game_name = :game_name WHERE game_id = :id',
        [
            'id' => $id,
            'game_id' => $request->game_id,
            'player_id' => $request->player_id,
            'game_name' => $request->game_name,
        ]
        );

        return redirect()->route('games.index')->with('success', 'Save updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Supplier $supplier)
    // {
    //     $supplier->delete();
    //     return redirect()->route('players.index')
    //                     ->with('success','Supplier deleted successfully');
    // }
    public function destroy($id)
    {
        DB::update('UPDATE games SET deleted_at = NOW() WHERE game_id = :game_id', ['game_id' => $id]);
    
        return redirect()->route('games.index')
                        ->with('success','Save deleted successfully');
    }
    
    public function deletelist()
    {
        // $players = Supplier::onlyTrashed()->paginate(10);
        $games = DB::table('games')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/games/trash',compact('games'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $players = Supplier::withTrashed()->where('supplierid',$id)->restore();
        DB::update('UPDATE games SET deleted_at = NULL WHERE game_id = :game_id', ['game_id' => $id]);
        return redirect()->route('games.index')
                        ->with('success','Save restored successfully');
    }
    public function deleteforce($id)
    {
        // $players = Supplier::withTrashed()->where('SupplierID',$id)->forceDelete();
        DB::delete('DELETE FROM games WHERE game_id = :game_id', ['game_id' => $id]);
        return redirect()->route('games.index')
                        ->with('success','Save deleted permanently');
    }
}