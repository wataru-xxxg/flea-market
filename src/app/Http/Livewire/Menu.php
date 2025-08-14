<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Menu extends Component
{
    public $mylist = false;
    public $search = "";
    protected $listeners = [
        'searchUpdated' => 'handleSearchUpdate'
    ];

    public function mount($mylist = false)
    {
        $this->mylist = $mylist;
    }

    public function handleSearchUpdate($search)
    {
        $this->search = $search;
    }

    public function render()
    {
        $mylist = $this->mylist;
        return view('livewire.menu', compact('mylist'));
    }
}
