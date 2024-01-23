<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;

class PostIndex extends Component
{
    //Paginacion para un conponenete livewire
    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search;
    //Reseteo de paginacion para lograr la busqueda correcta en todas la paginación
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::where('user_id', auth()->user()->id)
            ->where('name', 'LIKE', "%" . $this->search . "%")
            ->latest('id')
            ->paginate(3);
        return view('livewire.admin.post-index', compact('posts'));
    }
}
