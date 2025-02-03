<?php
namespace App\Traits\Tests;

use App\Models\Cart;
use App\Models\Order;
use App\Helpers\CartHelper; 
use App\Helpers\OrderHelper;



trait OrderTest {

    protected $cartHelper;
    protected $orderHelper;
    protected $cart;
    protected $order;

    protected function createCart() {
        $this->cart = (new CartHelper)->createTestCart();
        return $this->cart;
    }

    protected function createOrder( $user_id=null, $is_paid=false ) {
        $this->cart = (new CartHelper)->createTestCart( $user_id );
        $this->order = (new OrderHelper)->createTestOrder($this->cart['Model']->id, $is_paid);
        return $this->order;
    }

    protected function removeCart() {
        $this->cart['Model']->delete();
    }

    protected function removeOrder() {
        $this->order->delete();
        $this->cart['Model']->delete();
    }
    
    protected function removeLatestCart() {
        Cart::latest()->first()->delete();
    }

    protected function getLatestCart() {
        return Cart::latest()->first();
    }

    protected function getLatestOrder() {
        return Order::latest()->first();
    }

    protected function removeLatestOrder() {
        Order::latest()->first()->delete();
    }

    protected function removeAllOrders() {
        Order::truncate();
    }

    protected function removeAllCarts() {
        Cart::truncate();
    }


}