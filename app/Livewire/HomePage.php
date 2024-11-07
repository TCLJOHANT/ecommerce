<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inicio - Ecommerce')] //se usa para el title de la pagina
class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::query()->where('is_active',true)->get();
        $categories = Category::query()->where('is_active',true)->get();
        return view('livewire.home-page',[
            'brands'=>$brands,
            'categories'=>$categories,
        ]);
    }
}
