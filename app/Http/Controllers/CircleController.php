<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use Illuminate\Http\Request;

class CircleController extends Controller
{

    public function index()
    {
        $circle = Circle::all();
        return response()->json($circle);
    }

    public function show($id)
    {
        $circle = Circle::findOrFail($id);
        return response()->json($circle);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'path_id' => 'required|exists:paths,id',
        ]);

        $circle = Circle::create($request->all());

        return response()->json($circle, 201);
    }


    public function destroy($id)
    {
        $circle = Circle::findOrFail($id);

        $circle->delete();

        return response()->json(null, 204);
    }





}
