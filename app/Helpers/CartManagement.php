<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement {
    //agregar item al carro
    static public function addItemToCart($product_id) {
        $cart_items = self::getCartItemsFromCookie();
        $exiting_item = null;
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                $exiting_item = $key;
                break;
            }
        }
        if($exiting_item !== null) {
            $cart_items[$exiting_item]['quantity']++;
            $cart_items[$exiting_item]['total_amount'] = $cart_items[$exiting_item]['quantity'] * $cart_items[$exiting_item]['unit_amount'];
        }else{
            $product = Product::where('id',$product_id)->first(['id','name','price','images']);
            if($product) { 
                $cart_items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    //agregar item al carro segun cantidad
    static public function addItemToCartWhithQuantity($product_id, $quantity = 1) {
        $cart_items = self::getCartItemsFromCookie();
        $exiting_item = null;
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                $exiting_item = $key;
                break;
            }
        }
        if($exiting_item !== null) {
            $cart_items[$exiting_item]['quantity'] = $quantity;
            $cart_items[$exiting_item]['total_amount'] = $cart_items[$exiting_item]['quantity'] * $cart_items[$exiting_item]['unit_amount'];
        }else{
            $product = Product::where('id',$product_id)->first(['id','name','price','images']);
            if($product) { 
                $cart_items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantity' => $quantity,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    //remover item del carro
    static public function removeCartItem($product_id) {
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
                break;
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
        
    }
    //agregar items al cookie
    public static function addCartItemsToCookie($cart_items) {
       Cookie::queue('cart_items',json_encode($cart_items),60 * 24 * 30);
    }

    //limpiar el carro
    static public function clearCartItems() {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    //obtener los items del carro de la cookie
    static public function getCartItemsFromCookie() {
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!$cart_items){
            return [];
        };
        return $cart_items;
    }

    //incrementar el cantidad de un item en el carro
    static public function incrementQuantityToCartItem($product_id) {
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    //decrementar el cantidad de un item en el carro
    static public function decrementQuantityToCartItem($product_id) {
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $item) {
            if($item['product_id'] == $product_id && $cart_items[$key]['quantity'] > 1) {
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    //calcular el total del carro
    static public function calculateGrandTotal(array $items) {
        return array_sum(array_column($items,'total_amount'));
    }
}