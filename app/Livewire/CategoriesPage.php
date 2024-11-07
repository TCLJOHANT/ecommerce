<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categorias - Ecommerce')] //se usa para el title de la pagina
class CategoriesPage extends Component
{
    public function render()
    {
        $categories = Category::query()->where('is_active',true)->get();
        return view('livewire.categories-page',[
            'categories'=>$categories,
        ]);
    }
}
