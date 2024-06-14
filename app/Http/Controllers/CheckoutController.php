<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Book;
use App\Models\Purchase;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function showCheckout(Request $request)
    {
        $user = $request->user();
        $cartItems = collect(); // Initialize as an empty collection

        if ($user) {
            $cartItems = CartItem::with('book')->where('user_id', $user->id)->get()->map(function ($item) {
                return [
                    'id' => $item->book->id,
                    'title' => $item->book->title,
                    'price' => $item->book->price,
                ];
            });
        } else {
            $cart = $request->session()->get('cart', []);
            foreach ($cart as $item) {
                $book = Book::find($item['id']);
                if ($book) {
                    $cartItems->push([
                        'id' => $book->id,
                        'title' => $book->title,
                        'price' => $book->price,
                    ]);
                }
            }
        }

        $totalAmount = $cartItems->sum('price');

        return view('checkout.index', compact('cartItems', 'totalAmount'));
    }

    public function processCheckout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Calculate total amount
            $totalAmount = 0;
            $user = $request->user();
            $cartItems = collect();

            if ($user) {
                $cartItems = CartItem::with('book')->where('user_id', $user->id)->get()->map(function ($item) {
                    return [
                        'id' => $item->book->id,
                        'title' => $item->book->title,
                        'price' => $item->book->price,
                    ];
                });
            } else {
                $cart = $request->session()->get('cart', []);
                foreach ($cart as $item) {
                    $cartItems->push((object) $item);
                }
            }

            foreach ($cartItems as $item) {
                $totalAmount += $item->price;
            }

            // Create charge using Stripe
            $charge = Charge::create([
                'amount' => $totalAmount * 100, // Stripe requires amount in cents
                'currency' => 'usd',
                'description' => 'Book Purchase',
                'source' => $request->stripeToken,
            ]);

            // Clear cart after successful payment
            $this->clearCart($user, $request);

            // Save purchase information
            $this->savePurchaseInfo($user, $cartItems);

            return redirect()->route('checkout.success')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            Log::error('Error processing checkout', ['error' => $e->getMessage()]);
            return redirect()->route('checkout.show')->with('error', $e->getMessage());
        }
    }

    private function clearCart($user, Request $request)
    {
        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
        } else {
            $request->session()->forget('cart');
        }
    }

    private function savePurchaseInfo($user, $cartItems)
    {
        foreach ($cartItems as $item) {
            // Save purchase in purchases table
            $purchase = new Purchase();
            $purchase->book_id = $item['id'];
            $purchase->user_id = $user ? $user->id : null;
            $purchase->save();

            // Handle book availability logic
            $book = Book::find($item['id']);
            if ($book) {
                $book->available = false;
                $book->save();
            }
        }
    }
}
