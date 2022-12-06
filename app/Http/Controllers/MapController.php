<?php
    
namespace App\Http\Controllers;
    
use App\Models\Map;
use Illuminate\Http\Request;
use DB;
    
class MapController extends Controller
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
        $maps = DB::table('maps')->where('game_id','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('maps.index',compact('maps'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maps.create');
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
            'map_id' => 'required',
            'game_id' => 'required',
            'score' => 'required',
        ]);
        
        DB::insert('INSERT INTO maps(map_id, game_id, score) VALUES (:map_id, :game_id, :score)',
        [
            'map_id' => $request->map_id,
            'game_id' => $request->game_id,
            'score' => $request->score,
        ]
        );

        return redirect()->route('maps.index')->with('success', 'Map added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(map $map)
    {
        return view('maps.show',compact('map'));
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
        $map = DB::table('maps')->where('map_id', $id)->first();
        return view('maps.edit',compact('map'));
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
            'map_id' => 'required',
            'game_id' => 'required',
            'score' => 'required',
        ]);

        DB::update('UPDATE maps SET map_id = :map_id, game_id = :game_id, score = :score WHERE map_id = :id',
        [
            'id' => $id,
            'map_id' => $request->map_id,
            'game_id' => $request->game_id,
            'score' => $request->score,
        ]
        );

        return redirect()->route('maps.index')->with('success', 'Map updated successfully');
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
        DB::update('UPDATE maps SET deleted_at = NOW() WHERE map_id = :map_id', ['map_id' => $id]);
    
        return redirect()->route('maps.index')
                        ->with('success','Map deleted successfully');
    }
    
    public function deletelist()
    {
        // $players = Supplier::onlyTrashed()->paginate(10);
        $maps = DB::table('maps')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/maps/trash',compact('maps'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $players = Supplier::withTrashed()->where('supplierid',$id)->restore();
        DB::update('UPDATE maps SET deleted_at = NULL WHERE map_id = :map_id', ['map_id' => $id]);
        return redirect()->route('maps.index')
                        ->with('success','Map restored successfully');
    }
    public function deleteforce($id)
    {
        // $players = Supplier::withTrashed()->where('SupplierID',$id)->forceDelete();
        DB::delete('DELETE FROM maps WHERE map_id = :map_id', ['map_id' => $id]);
        return redirect()->route('maps.index')
                        ->with('success','Map deleted permanently');
    }
}