<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;

class StripeController extends Controller
{
  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function stripe()
  {
    return view('stripe');
  }

  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function stripePost(Request $request)
  {
    Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $customer = \Stripe\Customer::create([
      'name' => 'iqbal dhillon',
      'address' => [
        'line1' => '510 Townsend St',
        'postal_code' => '98140',
        'city' => 'San Francisco',
        'state' => 'CA',
        'country' => 'US',
      ],
      'email' => 'guriqbal.d@webdew.com',
      'source' => $request->stripeToken
    ]);
    $stripe = new \Stripe\StripeClient(
      env('STRIPE_SECRET')
    );

    $customer_id = $customer->id;

    $product = $stripe->products->create([
      'name' => 'Diamond',
      'id'   => '123',
      'metadata' => [
        'name' => "silver",
        'last-date' => '30-7-2021'
      ]
    ]);

    $product_id = $product->id;

    $price = $stripe->prices->create([
      'unit_amount' => 2000,
      'currency' => 'usd',
      'recurring' => ['interval' => 'month'],
      'product' => $product_id,
    ]);

    $price_id = $price->id;

    $subscription = $stripe->subscriptions->create([
      'customer' => $customer_id,
      'items' => [
        ['price' => $price_id],
      ],
      'metadata' => [
        'start_date' => '30-7-2021',
        'total_months' => '11',
        'end_date' => '30-5-2022'
      ]

    ]);

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

    echo "<pre>";
    print_r($subscription);
    die;

    Session::flash('success', 'Payment successful!');

    return back();
  }
}
