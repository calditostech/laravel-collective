<?php

namespace App\Http\Livewire;

use App\Product;
use Livewire\Component;

class Products extends Component
{
    protected $selected = [
        'prices' => [],
        'categories' => []
    ];

    protected $listeners = ['updatedSidebar' => 'setSelected'];

    public function render()
    {
        $products = Product::withFilters(
            $this->selected['prices'],
            $this->selected['categories']
        )->get();

        return view('livewire.products', compact('products'));
    }

    public function setSelected($selected)
    {
        $this->selected = $selected;
    }
}
