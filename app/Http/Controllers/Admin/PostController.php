<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.posts.index')->only('index');
        $this->middleware('can:admin.posts.create')->only('create', 'store');
        $this->middleware('can:admin.posts.edit')->only('edit', 'update');
        $this->middleware('can:admin.posts.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $file = $request->file('file');

        $post = Post::create($request->all());
        if ($file) {
            //Mover imagen de temporales a carpeta /public/storage/posts
            $url = Storage::disk('public')->put('posts', $file);
            $post->image()->create(['url' => $url]);
        }

        Cache::flush();

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }
        return redirect()->route('admin.posts.edit', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show($post)
    {
        return view('admin.posts.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('author', $post);
        $categories = Category::pluck('name', 'id');
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('author', $post);
        $post->update($request->all());
        //Actualizar Tags
        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }
        //Actualizar Imagen
        if ($request->file('file')) {

            $file = $request->file('file');

            //Mover imagen de temporales a carpeta /public/storage/posts
            $url = Storage::disk('public')->put('posts', $file);

            if ($post->image) {
                Storage::disk('public')->delete($post->image->url);
                $post->image->update(['url' => $url]);
            } else {

                $post->image()->create(['url' => $url]);
            }
        }
        //Actualizar cache borra todas las variables
        Cache::flush();

        return redirect()->route('admin.posts.edit', $post)->with('info', 'El post se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('author', $post);
        $post->delete();
        //Borrar todas las variables de la cache
        Cache::flush();

        return redirect()->route('admin.posts.index')->with('info', 'El post se elimino con exito');
    }
}