<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class PreviewItemImage extends Component
{
    use WithFileUploads;
    public $user;
    public $image;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('livewire.preview-item-image');
    }
}
