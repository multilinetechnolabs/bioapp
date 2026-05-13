<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Mail;
use URL;
use Session;

use App\Models\Order;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('app.pages.products.index');
    }

    public function bio()
    {
        return redirect()->route('app.products.index');
    }

    public function chakra()
    {
        return redirect()->route('app.products.index');
    }

    //only load shipping address view if cart have data
    public function shippingAddress()
    {
        $data = [];
        $data['cart'] = Session::get('cart');

        if (!empty($data['cart'])) {
            return view('app.pages.products.shipping_address', $data);
        } else {
            return redirect()->to(URL::route('app.user.orders'))->with('message.fail', 'Order Failed. An unexpected error occurred, please try again.');
        }
    }

    //get product and redirect to shipping address
    public function checkoutWithShipping(Request $request)
    {
        $product_id =  $request->product_id;
        $quantity = $request->quantity;
        
        $product = Product::find($product_id);
        $cart = array(
            "product_id" => $product->id,
            "weight" =>  floatval(explode(' ', $product->weight, 2)[0]) ,
            "description" => $product->size.' - '.$product->description,
            "quantity" => strval($quantity),
        );

        Session::put('cart', $cart);

        return redirect('/products/shipping_address');
    }

    public function checkout(Request $request)
    {
        $scart = Session::get('cart');
        $srate = Session::get('rate');

        if ($request->shipping_day_set == 1) {
            $shipping_day_set ='1 day';
        } else {
            $shipping_day_set ='1-3 days';
        }

        if (!empty($scart) && !empty($srate)) {
            $order = new Order([
                'user_id' => Auth::user()->id,
                'product_id' => $scart['product_id'],
                'quantity' =>  $scart['quantity'],
                'description' =>  $scart['description'],
                'shipping_rate' =>  $srate['shipping_rate'],
                'will_shipping' =>  $srate['will_shipping'],
                'shipping_service' =>  $srate['shipping_service'],
                'shipping_address' =>  $request->shipping_address,
                'shipping_zip' =>  $srate['shipping_zip'],
                'shipping_day_set' =>  $shipping_day_set
            ]);

            if ($order->save()) {
                $email_to = $order->user->email;
                $email_bcc = env('MAIL_FROM_ADDRESS_BCC');

                Mail::to($email_to)->bcc($email_bcc)->send(new \App\Mail\OrderEmail($order));

                return redirect()->to(URL::route('app.user.orders'))->with('message.success', 'You have successfully ordered our products, kindly expect an email from us for more details. Thank you.');
            } else {
                return redirect()->to(URL::route('app.user.orders'))->with('message.fail', 'Order Failed. An unexpected error occurred, please try again.');
            }
        } else {
            return redirect()->to(URL::route('app.user.orders'))->with('message.fail', 'Order Failed. An unexpected error occurred, please try again.');
        }
    }
}
