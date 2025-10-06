<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use Illuminate\Http\Request;

class PostController extends Controller
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

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'files.*' => 'file|max:51200', // Max 50MB per file, change as needed
        ]);

        // Save the ressource
        $ressource = Post::create([
            'course_id' => $request->input('course_id'),
            'content' => $request->input('content'),
        ]);

        // Save files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $path = $file->store('post-files', 'public');

                File::create([
                    'post_id' => $ressource->id,
                    'file_path' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'ressource created successfully!',
            'ressource' => $ressource->load('files')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId)
    {
        $posts = Post::where('course_id', $courseId)
            ->with(['course.teacher' => function ($query) {
            $query->select('id', 'name');
            }, 'files']) 
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(
            $posts
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->files) {
            foreach ($post->files as $file) {
            $filePath = storage_path('app/public/' . $file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $file->delete();
            }
        }
        
        $post->course()->dissociate();

        $post->delete();

        return response()->json([
            'message' => 'ressource deleted successfully!'
        ]);
    }
}
