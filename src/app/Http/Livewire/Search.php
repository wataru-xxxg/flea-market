<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Search extends Component
{
    public $mylist = false;
    public $search = "";

    public function updatedSearch($value)
    {
        $this->search = $value;
        $this->emit('searchUpdated', $this->search);
    }
    public function render()
    {
        return view('livewire.search');
    }
}
