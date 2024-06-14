<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\CartItem;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return redirect()->back()->with('error', 'Book not found.');
        }

        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where('user_id', $user->id)->where('product_id', $id)->first();

            if (!$cartItem) {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $id
                ]);
            }
        } else {
            $cart = $request->session()->get('cart', []);
            if (!isset($cart[$id])) {
                $cart[$id] = [
                    'id' => $book->id,
                    'title' => $book->title,
                    'price' => $book->price
                ];
            }
            $request->session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Book added to cart successfully.');
    }

    public function showCart(Request $request)
    {
        $user = $request->user();
        $cart = collect();

        if ($user) {
            $cartItems = CartItem::with('book')->where('user_id', $user->id)->get();
            foreach ($cartItems as $item) {
                $cart->push([
                    'id' => $item->book->id,
                    'title' => $item->book->title,
                    'price' => $item->book->price,
                ]);
            }
        } else {
            $cart = collect($request->session()->get('cart', []));
        }

        $totalPrice = $cart->sum('price');

        return view('cart.show', compact('cart', 'totalPrice'));
    }

    public function removeFromCart(Request $request, $id)
    {
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::where('user_id', $user->id)->where('product_id', $id)->first();
            if ($cartItem) {
                $cartItem->delete();
                return redirect()->back()->with('success', 'Item removed from cart successfully.');
            }
        } else {
            $cart = $request->session()->get('cart', []);
            if (array_key_exists($id, $cart)) {
                unset($cart[$id]);
                $request->session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Item removed from cart successfully.');
            }
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }
}
