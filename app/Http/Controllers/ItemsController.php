<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::with('type')->paginate(2);

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_id' => 'required',
            'name' => 'required|min:3',
            'image' => 'image|file|max:1024'
        ]);

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('post-images');
        }

        $items = Item::create($validatedData);
        $type = Type::find($items->type_id);

        return response()->json([
            'message' => 'Data items berhasil ditambahkan.',
            'data' => [
                'id' => $items->id,
                'type_id' => $items->type_id,
                'name' => $items->name,
                'image' => $items->image,
                'type' => [
                    'id' => $type->id,
                    'name' => $type->name
                ]
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'type_id' => 'required',
            'image' => 'required'
        ]);

        if ($request->file('image')) {
            if ($item->image) {
                Storage::delete($item->image);
            }
            $validatedData = $request->validate([
                'image' => 'image|file|max:1024'
            ]);
            $validatedData['image'] = $request->file('image')->store('post-images');
        }

        Item::where('id', $id)->update($validatedData);
        return response()->json([
            'message' => 'Data items berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $items = Item::find($id);
        if ($items->image) {
            Storage::delete($items->image);
        }
        Item::destroy($id);

        return response()->json([
            'message' => 'Data items berhasil dihapus.'
        ]);
    }

    public function getType()
    {
        $type = Type::all();

        return response()->json($type);
    }
}
