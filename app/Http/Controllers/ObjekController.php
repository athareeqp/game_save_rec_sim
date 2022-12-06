<?php
    
namespace App\Http\Controllers;
    
use App\Models\Objek;
use Illuminate\Http\Request;
use DB;
    
class ObjekController extends Controller
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
        $objeks = DB::table('objeks')->where('map_id','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('objeks.index',compact('objeks'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('objeks.create');
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
            'objek_id' => 'required',
            'map_id' => 'required',
            'position' => 'required',
        ]);
        
        DB::insert('INSERT INTO objeks(objek_id, map_id, position) VALUES (:objek_id, :map_id, :position)',
        [
            'objek_id' => $request->objek_id,
            'map_id' => $request->map_id,
            'position' => $request->position,
        ]
        );

        return redirect()->route('objeks.index')->with('success', 'Object added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(objek $objek)
    {
        return view('objeks.show',compact('objek'));
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
        $objek = DB::table('objeks')->where('objek_id', $id)->first();
        return view('objeks.edit',compact('objek'));
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
            'objek_id' => 'required',
            'map_id' => 'required',
            'position' => 'required',
        ]);

        DB::update('UPDATE objeks SET objek_id = :objek_id, map_id = :map_id, position = :position WHERE objek_id = :id',
        [
            'id' => $id,
            'objek_id' => $request->objek_id,
            'map_id' => $request->map_id,
            'position' => $request->position,
        ]
        );

        return redirect()->route('objeks.index')->with('success', 'Object updated successfully');
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
        DB::update('UPDATE objeks SET deleted_at = NOW() WHERE objek_id = :objek_id', ['objek_id' => $id]);
    
        return redirect()->route('objeks.index')
                        ->with('success','Object deleted successfully');
    }
    
    public function deletelist()
    {
        // $players = Supplier::onlyTrashed()->paginate(10);
        $objeks = DB::table('objeks')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/objeks/trash',compact('objeks'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $players = Supplier::withTrashed()->where('supplierid',$id)->restore();
        DB::update('UPDATE objeks SET deleted_at = NULL WHERE objek_id = :objek_id', ['objek_id' => $id]);
        return redirect()->route('objeks.index')
                        ->with('success','Objek restored successfully');
    }
    public function deleteforce($id)
    {
        // $players = Supplier::withTrashed()->where('SupplierID',$id)->forceDelete();
        DB::delete('DELETE FROM objeks WHERE objek_id = :objek_id', ['objek_id' => $id]);
        return redirect()->route('objeks.index')
                        ->with('success','Objek deleted permanently');
    }
}