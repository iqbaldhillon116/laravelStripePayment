<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Auth;
use Crypt;
use Redirect;
use Hash;
use DB;
use App\User;
use App\Credential;
use Stripe\Error\Card;
use Stripe;
use Session;

class StripePayment extends Controller
{
    public function GetPayment()
    {
        return view('card');
    }

    public function PostPayment(Request $request)
    {
        try {
            $data         = $request->all();
            $email        = $data["email"];
            $stripe_token = $data["stripe_token"];
            $product_name = $data["product_name"];
            $product_id   = $data["product_id"];
            $amount       = $data["amount"] * 100;
            $months       = $data['months'];
            $today = time();

            // Cancel them 30 days from today
            $cancelAt = strtotime("+$months months", $today);


            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            //create customer
            $customer = \Stripe\Customer::create([
                'name' => 'iqbal dhillon',
                'address' => [
                    'line1' => '510 Townsend St',
                    'postal_code' => '98140',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'country' => 'US',
                ],
                'email' => $email,
                'source' => $stripe_token
            ]);
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );

            $customer_id = $customer->id;

            //create  product
            $product = $stripe->products->create([
                'name' => $product_name,
                'id'   => $product_id,
                'metadata' => [
                    'name' => $product_name,
                    'last-date' => '30-7-2021'
                ]
            ]);

            $product_id = $product->id;

            //create recurring price
            $price = $stripe->prices->create([
                'unit_amount' => $amount,
                'currency' => 'usd',
                'recurring' => ['interval' => 'month'],
                'product' => $product_id,
            ]);

            $price_id = $price->id;

            //create subscription
            $subscription = $stripe->subscriptions->create([
                'customer' => $customer_id,
                'items' => [
                    ['price' => $price_id],
                ],
                'metadata' => [
                    'start_date' => $today,
                    'total_months' => $months,
                    'end_date' => $cancelAt
                ],
                "cancel_at" => $cancelAt

            ]);


            $res = ['status' => 'true', 'message' => 'payment has been successfully done'];
            // $intent = \Stripe\PaymentIntent::create([
            //     'amount' => 200,
            //     'currency' => 'inr',
            //     'customer' => 'cus_JwnPgfG5Qu7rUn',
            //   ]);


            // Stripe\Charge::create ([
            //         "amount" => 100 * 100,
            //         "currency" => "usd",
            //         "source" => $request->stripeToken,
            //         "description" => "Test payment from tutsmake.com."
            // ]);

            echo $_GET['callback'] . "(" . json_encode($res) . ");";
            exit;
        } catch (\Exception $e) {
           
            $message = $e->getMessage();
            $res = ['status' => 'false', 'message' => "Payment Failed.$message"];
            echo $_GET['callback'] . "(" . json_encode($res) . ");";
            exit;
        }
    }

    public function HubDbRow($email=''){
        
    }
}
