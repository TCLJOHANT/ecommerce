<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('cart - Ecommerce')]
class CartPage extends Component
{
    use LivewireAlert;
    public $cart_items = [];
    public $gran_total;
    public function mount() : void {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        $this->gran_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function removeItem($product_id) {
        $this->cart_items = CartManagement::removeCartItem($product_id);
        $this->gran_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count : count($this->cart_items))->to(Navbar::class);
        $this->alert('success', 'El producto fue removido del carrito',[
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
    public function increaseQty($product_id) {
        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id);
        $this->gran_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count : count($this->cart_items))->to(Navbar::class);
    }
    public function decreseQty($product_id) {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
        $this->gran_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count : count($this->cart_items))->to(Navbar::class);
    }
    public function render()
    {
        return view('livewire.cart-page');
    }
}
