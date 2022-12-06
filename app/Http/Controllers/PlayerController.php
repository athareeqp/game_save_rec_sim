<?php
    
namespace App\Http\Controllers;
    
use App\Models\Player;
use Illuminate\Http\Request;
use DB;
    
class PlayerController extends Controller
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
        $players = DB::table('players')->where('nickname','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('players.index',compact('players'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('players.create');
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
            'player_id' => 'required',
            'nickname' => 'required',
            'email' => 'required',
        ]);
        
        DB::insert('INSERT INTO players(player_id, nickname, email) VALUES (:player_id, :nickname, :email)',
        [
            'player_id' => $request->player_id,
            'nickname' => $request->nickname,
            'email' => $request->email,
        ]
        );

        return redirect()->route('players.index')->with('success', 'Player added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(player $player)
    {
        return view('players.show',compact('player'));
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
        $player = DB::table('players')->where('player_id', $id)->first();
        return view('players.edit',compact('player'));
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
            'player_id' => 'required',
            'nickname' => 'required',
            'email' => 'required',
        ]);

        DB::update('UPDATE players SET player_id = :player_id, nickname = :nickname, email = :email WHERE player_id = :id',
        [
            'id' => $id,
            'player_id' => $request->player_id,
            'nickname' => $request->nickname,
            'email' => $request->email,
        ]
        );

        return redirect()->route('players.index')->with('success', 'Player updated successfully');
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
        DB::update('UPDATE players SET deleted_at = NOW() WHERE player_id = :player_id', ['player_id' => $id]);
    
        return redirect()->route('players.index')
                        ->with('success','Player deleted successfully');
    }
    
    public function deletelist()
    {
        // $players = Supplier::onlyTrashed()->paginate(10);
        $players = DB::table('players')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/players/trash',compact('players'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $players = Supplier::withTrashed()->where('supplierid',$id)->restore();
        DB::update('UPDATE players SET deleted_at = NULL WHERE player_id = :player_id', ['player_id' => $id]);
        return redirect()->route('players.index')
                        ->with('success','Player restored successfully');
    }
    public function deleteforce($id)
    {
        // $players = Supplier::withTrashed()->where('SupplierID',$id)->forceDelete();
        DB::delete('DELETE FROM players WHERE player_id = :player_id', ['player_id' => $id]);
        return redirect()->route('players.index')
                        ->with('success','Player deleted permanently');
    }
}