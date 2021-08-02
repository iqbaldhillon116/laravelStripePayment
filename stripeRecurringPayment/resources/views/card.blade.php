<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="	https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>




<body>
    <div class="container">
        <h2>Basic Card</h2>
        <div class="card">
            <div class="card-body">
                <form action="/action_page.php">
                    <label>Price:<input id="price" disabled value="1100">

                        <div class="form-check">
                            <label class="form-check-label" for="radio1">
                                <input type="radio" class="form-check-input" id="radio1" name="optradio" value="option1"
                                    checked>Buy Now
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" for="radio2">
                                <input type="radio" class="form-check-input" id="radio2" name="optradio"
                                    value="option2">Lease
                            </label>
                        </div>
                        <div id="slider">
                            {{-- <button class="btn btn-primary" id="plus">+</button> --}}
                            <div class="form-group">
                                <input type="range" class="custom-range" min="1" max="12" step="1" id="ex6" value="12">
                                <span><span id="ex6Val">12</span>months</span>
                                <br>
                                <label>Price per month:</label>
                                <p id="ppmonth"> </p>
                            </div>
                            {{-- <button class="btn btn-primary" id="minus">-</button> --}}

                        </div>

                        <!-- stripe -->

                        <input type="submit" class="btn btn-primary buyNow" value="Buy Now"
                            data-key="{{env('STRIPE_KEY')}}" data-amount="100" data-currency="usd" data-name="Ecommerce"
                            data-email="" id="stripe" />

                        <script src="https://checkout.stripe.com/v2/checkout.js"></script>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $(document).on('click', '#stripe', function (event) {
                                    event.preventDefault();
                                    var amount = $("#stripe").attr("data-amount");
                                    amount = amount / 100;

                                    var currency = $("#stripe").attr("data-currency");
                                    var $button = $(this),
                                        $form = $button.parents('form');
                                    var opts = $.extend({
                                        amount: amount,
                                        currency: 'usd',

                                    }, $button.data(), {
                                        token: function (result) {
                                            $(".loader-wrapper").fadeIn("slow");
                                            //console.log(result);
                                            $form.append($('<input>').attr({
                                                id: "stripeToken",
                                                type: 'hidden',
                                                name: 'stripeToken',
                                                value: result.id
                                            }));

                                            var email = result.email;
                                            var stripe_token = result.id;
                                            var product_id = '123456789';
                                            var product_name = "testing";
                                            $.ajax({
                                                type: "GET",
                                                url: 'http://127.0.0.1:8000/stripePay?status=true&' +
                                                    $('#payment-form').serialize() +
                                                    '&amount=' + amount +
                                                    '&email=' + email +
                                                    '&currency=' + currency +
                                                    '&stripe_token=' +
                                                    stripe_token + '&product_id=' +
                                                    product_id + '&product_name=' +
                                                    product_name,
                                                    dataType: 'jsonp',

                                                success: function (data, status) {
                                                    console.log(data);
                                                    if (data.status === true) {
                                                        swal(data.message);
                                                        ClearAll();
                                                        var base_url = window
                                                            .location.origin;
                                                        window.location.replace(
                                                            "https://www.webdew.com/brand-domains"
                                                            );
                                                    } else {
                                                        swal(data.message);
                                                    }
                                                    $(".loader-wrapper")
                                                        .fadeOut("slow");
                                                },
                                                error: function (jqXhr, textStatus,
                                                    errorMessage) {
                                                    console.log(textStatus);
                                                    console.log(errorMessage);
                                                }
                                            });

                                        }
                                    });
                                    StripeCheckout.open(opts);
                                });
                            });

                        </script>
                        {{-- stripe buy ends --}}

                        {{-- stripe lease starts --}}

        

                        <input type="submit" class="btn btn-primary lease" value="Lease Now"
                            data-key="{{env('STRIPE_KEY')}}" data-amount="" data-currency="usd" data-name="Ecommerce"
                            data-email="" id="stripe2" />

                        <script src="https://checkout.stripe.com/v2/checkout.js"></script>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $(document).on('click', '#stripe2', function (event) {
                                    event.preventDefault();
                                    var amount = $("#stripe2").attr("data-amount");
                                    amount = amount / 100;


                                    var currency = $("#stripe2").attr("data-currency");
                                    var months = $("#ex6").val();


                                    var $button = $(this),
                                        $form = $button.parents('form');
                                    var opts = $.extend({
                                        amount: amount,
                                        currency: 'usd',

                                    }, $button.data(), {
                                        token: function (result) {
                                            $(".loader-wrapper").fadeIn("slow");

                                            $form.append($('<input>').attr({
                                                id: "stripeToken",
                                                type: 'hidden',
                                                name: 'stripeToken',
                                                value: result.id
                                            }));

                                            var email = result.email;
                                            var stripe_token = result.id;
                                            var product_id = '12759';
                                            var product_name = "testingrecurring7";
                                            $.ajax({
                                                type: "GET",

                                                url: 'http://127.0.0.1:8000/stripePay?status=true&' +
                                                    $('#payment-form').serialize() +
                                                    '&amount=' + amount +
                                                    '&email=' + email +
                                                    '&currency=' + currency +
                                                    '&stripe_token=' +
                                                    stripe_token + '&product_id=' +
                                                    product_id + '&product_name=' +
                                                    product_name +'&months='+months,
                                                    dataType: 'jsonp',

                                                success: function (data, status) {
                                                    console.log(data);
                                                    if (data.status === true) {
                                                        swal(data.message);
                                                        ClearAll();
                                                        var base_url = window
                                                            .location.origin;

                                                        window.location.replace(
                                                            "https://www.webdew.com/brand-domains"
                                                            );
                                                    } else {
                                                        swal(data.message);
                                                    }
                                                    $(".loader-wrapper")
                                                        .fadeOut("slow");
                                                },
                                                error: function (jqXhr, textStatus,
                                                    errorMessage) {
                                                    console.log(textStatus);
                                                    console.log(errorMessage);
                                                }
                                            });

                                        }
                                    });
                                    StripeCheckout.open(opts);
                                });
                            });

                        </script>

                </form>

            </div>
        </div>
    </div>
    {{-- stripe lease ends --}}
    <script>
        //radio button click changes
        $(document).ready(function () {
            $(".lease").hide();
            $("#slider").hide();
            $("#radio1").click(function () {
                $(".lease").hide();
                $(".buyNow").show();
                $("#slider").hide();
            });
            $("#radio2").click(function () {
                $(".lease").show();
                $(".buyNow").hide();
                $("#slider").show();
            });
        });
        //radio button click changes

        //slider range 
        $('#ex6').on('change', function (e) {
            var id = e.target.value;
            let price = $("#price").val();

            let lease_price = price / id;
            lease_price = lease_price.toFixed(1);

            $("#ppmonth").text(lease_price);
            $("#stripe2").attr('data-amount', lease_price * 100);
            document.getElementById("ex6Val").innerHTML = id;
        });
        $('#ex6').change();

        //slider range 

        //lease button click starts
        
        // $('#stripe2').click(function (event) {
        //     event.preventDefault();
        //     let lease_price =  $("#ppmonth").text();
        //     console.log(lease_price);
        //     $("#stripe2").attr('data-amount', lease_price * 100);
        // })
        //lease button click ends 


        //buttons
        $('#plus').click(function (event) {
            event.preventDefault();
            let value = $('#ex6').val();
            Number(value);
            value = value + 1;
            alert(value);
            $('#ex6').attr('value', value);
        })

        $('#minus').click(function (event) {
            event.preventDefault();
            let value = $('#ex6').val();
            Number(value);
            value = value - 1;
            alert(value);
            $('#ex6').attr('value', value);
        })
        //buttons

    </script>


</body>

</html>



{{-- 
<script src="https://js.stripe.com/v2/"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<div class="panel">
    <form action="payment.php" method="POST" id="paymentFrm">
        <div class="panel-heading">
            <h3 class="panel-title">Plan Subscription with Stripe</h3>
			
            <!-- Plan Info -->
            <p>
                <b>Select Plan:</b>
                <select name="subscr_plan" id="subscr_plan">
                   
                        <option value=""</option>
                   
                </select>
            </p>
        </div>
        <div class="panel-body">
            <!-- Display errors returned by createToken -->
            <div class="card-errors"></div>
			
            <!-- Payment form -->
            <div class="form-group">
                <label>NAME</label>
                <input type="text" name="name" id="name" placeholder="Enter name" required="" autofocus="">
            </div>
            <div class="form-group">
                <label>EMAIL</label>
                <input type="email" name="email" id="email" placeholder="Enter email" required="">
            </div>
            <div class="form-group">
                <label>CARD NUMBER</label>
                <input type="text" name="card_number" id="card_number" placeholder="1234 1234 1234 1234" maxlength="16" autocomplete="off" required="">
            </div>
            <div class="row">
                <div class="left">
                    <div class="form-group">
                        <label>EXPIRY DATE</label>
                        <div class="col-1">
                            <input type="text" name="card_exp_month" id="card_exp_month" placeholder="MM" maxlength="2" required="">
                        </div>
                        <div class="col-2">
                            <input type="text" name="card_exp_year" id="card_exp_year" placeholder="YYYY" maxlength="4" required="">
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="form-group">
                        <label>CVC CODE</label>
                        <input type="text" name="card_cvc" id="card_cvc" placeholder="CVC" maxlength="3" autocomplete="off" required="">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success" id="payBtn">Submit Payment</button>
        </div>
    </form>
</div>
 
<script>
// Set your publishable key
Stripe.setPublishableKey('pk_test_51HIGy1Et32QR8DEin6O2X1MaUaQeRLUmdx49imFUDG2A5sdTZ94Sl7Mqy0cGikPOnxwpWRDjB9pU4oBeQ2VWuE4O002IlB5paY');
 
// Callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        // Enable the submit button
        $('#payBtn').removeAttr("disabled");
        // Display the errors on the form
        $(".payment-status").html('<p>'+response.error.message+'</p>');
    } else {
        var form$ = $("#paymentFrm");
        // Get token id
        var token = response.id;
        // Insert the token into the form
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        // Submit form to the server
        form$.get(0).submit();
    }
}
 
$(document).ready(function() {
    // On form submit
    $("#paymentFrm").submit(function() {
        // Disable the submit button to prevent repeated clicks
        $('#payBtn').attr("disabled", "disabled");
		
        // Create single-use token to charge the user
        Stripe.createToken({
            number: $('#card_number').val(),
            exp_month: $('#card_exp_month').val(),
            exp_year: $('#card_exp_year').val(),
            cvc: $('#card_cvc').val()
        }, stripeResponseHandler);
		
        // Submit from callback
        return false;
    });
});
</script> --}}
