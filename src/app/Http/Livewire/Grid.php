<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Item;

class Grid extends Component
{
    public $mylist = false;
    public $search;
    public $results = [];

    public function mount()
    {
        if (is_null($this->search)) {
            $this->search = "";
        }

        $this->createResults();
    }

    protected $listeners = [
        'searchUpdated' => 'handleSearchUpdate'
    ];

    public function handleSearchUpdate($search)
    {
        $this->search = $search;

        $this->createResults();
    }

    private function createItemIds($favorites)
    {
        $itemIds = [];

        foreach ($favorites as $favorite) {
            $itemId = $favorite->item->id;

            array_push($itemIds, $itemId);
        }

        return $itemIds;
    }

    private function createResults()
    {
        $user = Auth::user();
        $keyword = $this->search;

        if (is_null($user)) {
            $this->mylist ? $this->results = [] : $this->results = Item::likeName($keyword)->get();
            return;
        }

        $userId = $user->id;

        if ($this->mylist) {
            $favorites = $user->favorites;
            $itemIds = $this->createItemIds($favorites);

            count($itemIds) == 0 ? $this->results = [] : $this->results = Item::likeName($keyword)->whereInItemIds($itemIds)->notMyItems($userId)->get();
        } else {
            $keyword === "" ? $this->results = Item::notMyItems($userId)->get() : $this->results = Item::likeName($keyword)->notMyItems($userId)->get();
        }

        if (count($this->results) == 0) {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.grid');
    }
}
