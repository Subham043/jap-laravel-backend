<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>JAP</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('fontawesome-all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('iofrm-style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('iofrm-theme11.css') }}">

        <style>
            body, .form-body, .form-content {
                background-color: #28ba62;
            }
        </style>

    </head>
    <body class="antialiased">
        <div class="form-body">
            <div class="row">

                <div class="col-lg-12">
                    <div class="website-logo-inside mb-0 text-center pt-5">
                        <a href="#">
                            <div class="logo">
                                <img src="{{asset('logo.webp')}}" />
                            </div>
                        </a>
                    </div>
                    <h3 class="text-center">JAP</h3>
                    <div class="text-center p-2" id="payment_failed" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/hrqwmuhr.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px">
                        </lord-icon>
                        <div class="mt-4">
                            <h5 class="mb-3">Oops payment failed!</h5>
                            <p class="text-white mb-4"> The transfer was not successfully received by us.</p>
                            <div class="hstack gap-2 justify-content-center">
                                <button type="button" class="btn btn-light">Cancel</button>
                                <button onclick="setPrice()" class="btn btn-success">Try Again</button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center p-2" style="display: none;" id="payment_cancelled">
                        <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                        </lord-icon>
                        <div class="mt-4 pt-4">
                            <h5>Uh oh, You cancelled the payment!</h5>
                            <p class="text-white"> The payment was not successfully received by us.</p>
                            <!-- Toogle to second dialog -->
                            <button onclick="setPrice()" class="btn btn-warning">Make Payment</button>
                        </div>
                    </div>

                    <div class="text-center p-2" style="display: none;" id="payment_success">
                        <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px">
                        </lord-icon>

                        <div class="mt-4">
                            <h5 class="mb-3">Your Transaction was Successfull !</h5>
                            <p class="text-white mb-4"> Please wait till we verify your payment. Do not refresh the browser unless we have completed the verification.</p>
                            <div class="hstack gap-2 justify-content-center">
                                <div class="spinner-border text-white" role="status"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </body>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{ asset('axios.min.js') }}"></script>
    <script>

        var options;

        function setPrice() {
            options = {
                "key": "{{env('RAZORPAY_KEY')}}", // Enter the Key ID generated from the Dashboard
                "amount": parseInt({{$order->total_price}}) * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "order_id": "{{$order->razorpay_order_id}}",
                "currency": "INR",
                "name": "JAP",
                "description": "Order Transaction",
                "image": "{{ asset('logo.webp') }}",
                //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "handler": function(response) {
                    document.getElementById('payment_success').style.display = 'block';
                    document.getElementById('payment_cancelled').style.display = 'none';
                    document.getElementById('payment_failed').style.display = 'none';
                    verifyPayment(response);
                },

                "prefill": {
                    "name": "{{$order->billing_name}}",
                    "email": "{{$order->billing_email}}",
                    "contact": "+91{{$order->billing_phone}}"
                },
                "notes": {
                    "address": "Razorpay Corporate Office"
                },
                "theme": {
                    "color": "#28ba62"
                },
                "modal": {
                    "ondismiss": function() {
                        document.getElementById('payment_cancelled').style.display = 'block';
                        document.getElementById('payment_failed').style.display = 'none';
                        document.getElementById('payment_success').style.display = 'none';
                    }
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function(response) {
                // console.log(response);
                document.getElementById('payment_failed').style.display = 'block';
                document.getElementById('payment_cancelled').style.display = 'none';
                document.getElementById('payment_success').style.display = 'none';
            });
            rzp1.open();

        }

        window.onload = setPrice;

        const verifyPayment = async (data) => {
            try {
                const response = await axios.post('{{route('verify_payment', $order->receipt)}}', data)
                window.location.replace('{{route('success')}}');
            }catch (error){
                console.log(error);
                if(error?.response?.data?.errors?.razorpay_order_id){
                    errorToast(error?.response?.data?.errors?.razorpay_order_id[0])
                }
                if(error?.response?.data?.errors?.razorpay_payment_id){
                    errorToast(error?.response?.data?.errors?.razorpay_payment_id[0])
                }
                if(error?.response?.data?.errors?.razorpay_signature){
                    errorToast(error?.response?.data?.errors?.razorpay_signature[0])
                }
                if(error?.response?.data?.message){
                    errorToast(error?.response?.data?.message)
                }
            }finally{
                return false;
            }
        }

    </script>
</html>
