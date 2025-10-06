<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Wajib;

class WajibController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $wajib =  $request->wajib;
        $student = $request->student;

      $createdWajib =   Wajib::create([
            "record_id"=>$student["record"]["id"],
            "surat"=>$wajib["surat"],
            "from_aya"=>$wajib["from_aya"],
            "to_aya"=>$wajib["to_aya"],
            "due_date"=>$wajib["due_date"]??null,
        ]);

        return response()->json($createdWajib);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $wajib = Wajib::find($id);
        if (!$wajib) {
            return response()->json(['message' => 'Wajib not found'], 404);
        }
        $wajib->update($request->all());
        return response()->json($wajib);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wajib = Wajib::find($id);
        if (!$wajib) {
            return response()->json(['message' => 'Wajib not found'], 404);
        }
        $wajib->delete();
        return response()->json(['message' => 'Wajib deleted successfully']);
    }
}
