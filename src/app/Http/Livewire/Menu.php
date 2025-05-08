<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Menu extends Component
{
    public $search = "";
    public $mylist = false;
    protected $listeners = [
        'searchUpdated' => 'handleSearchUpdate'
    ];

    public function handleSearchUpdate($search)
    {
        $this->search = $search;
    }

    public function render()
    {
        return view('livewire.menu');
    }
}
