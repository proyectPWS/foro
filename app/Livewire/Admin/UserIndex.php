<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    //Reseteo de paginacion para lograr la busqueda correcta en todas la paginación
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $users = User::where('name', "like", "%" . $this->search . "%")
            ->orWhere('email', "like", "%" . $this->search . "%")->paginate(4);
        return view('livewire.admin.user-index', compact('users'));
    }
}
