<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AddToCartController extends Controller
{
    public function addToCart(Request $request)
    {
        // VALIDATION FIX — Use course_id correct column
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:course,course_id',
            'quantity'  => 'nullable|integer|min:1'
        ]);

        $user_id = $request->user_id;
        $quantity = $request->quantity ?? 1;

        // Correct course lookup using course_id
        $course = Course::where('course_id', $request->course_id)->first();

        if (!$course) {
            return response()->json([
                "status" => false,
                "message" => "Course not found"
            ], 404);
        }

        // Check if already exists in cart
        $cart = CartItem::where('user_id', $user_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($cart) {
            // update quantity
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            // create new
            $cart = CartItem::create([
                'user_id'   => $user_id,
                'course_id' => $request->course_id,
                'quantity'  => $quantity
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Added to Cart",
            "cart" => $cart
        ]);
    }


    public function cartList(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $list = CartItem::with('course')
            ->where('user_id', $request->user_id)
            ->get();

        return response()->json([
            "status" => true,
            "cart" => $list
        ]);
    }


    public function removeCartItem($id)
    {
        $cart = CartItem::find($id);

        if (!$cart) {
            return response()->json([
                "status" => false,
                "message" => "Item not found"
            ]);
        }

        $cart->delete();

        return response()->json([
            "status" => true,
            "message" => "Item removed"
        ]);
    }

    public function confirmCart(Request $request)
{
    $request->validate([
        "user_id" => "required|exists:users,id"
    ]);

    $cartItems = CartItem::with('course')
        ->where('user_id', $request->user_id)
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            "status" => false,
            "message" => "Cart is empty"
        ]);
    }

    foreach ($cartItems as $item) {
        Enrollment::create([
            "student_id" => $request->user_id,
            "course_id" => $item->course_id,
            "mrp" => $item->course->mrp,
            "sell_price" => $item->course->sell_price,
            "status" => "pending",
        ]);
    }

    CartItem::where("user_id", $request->user_id)->delete();

    return response()->json([
        "status" => true,
        "message" => "Courses moved to enrolments!"
    ]);
}

}
