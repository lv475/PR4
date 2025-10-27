<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use Illuminate\Http\Request;
use Inertia\Inertia; 

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user:id,name')->latest()->get(); 
 
        return Inertia::render('Posts/Index', [ 
            'posts' => $posts, 
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Post::class); 
 
        return Inertia::render('Posts/Create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $this->authorize('create', Post::class); 
 
        $data = $request->validate([ 
            'title' => ['required','string','max:255'], 
            'body'  => ['required','string'], 
        ]); 
 
        $data['user_id'] = auth()->id(); 
        Post::create($data); 
 
        return to_route('posts.index'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    public function edit(Post $post)
    {
        $this->authorize('update', $post); 
 
        return Inertia::render('Posts/Edit', [ 
            'post' => $post, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post); 
 
        $data = $request->validate([ 
            'title' => ['required','string','max:255'], 
            'body'  => ['required','string'], 
        ]); 
 
        $post->update($data); 
 
        return to_route('posts.index'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); 
 
        $post->delete(); 
 
        return to_route('posts.index');
    }
}
