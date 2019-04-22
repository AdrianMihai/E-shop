Express Checkout via Braintree SDK
--------------------------------------------------------------

This project is a PHP Web Application that demonstrates the "Express Checkout via Braintree SDK" payment experience using the Braintree client JavaScript SDKs and the server-side PHP SDK.  

"Express Checkout via Braintree SDK" allows you to integrate Express Checkout using the Braintree SDK without a Braintree account.  There are two Payment Flows in the demonstration.  Mouse-over the buttons in the Shopping Cart to review the Payment Flow diagrams.

Payment Flow 1: The Buyer selects Shipping Address and Wallet inside the PayPal mini-browser and completes the payment on the Seller's website. 

Payment Flow 2: The Buyer provides Shipping Address on the Seller's website then completes the payment inside the PayPal mini-browser. 


Prerequisites
-------------
1. The Braintree PHP SDK requires PHP version 5.4.0 or higher and the PHP cURL extension.
  
2. Web Server with PHP enabled.


Quick Start Demo Deployment Using XAMPP
---------------------------------------

1. Download PHP server.
Use a server such as XAMPP (https://www.apachefriends.org/index.html) to be able to host the Demo code sample.

2. Browse to the htdocs directory of xampp. Unzip the downloaded demo code folder and place it in this htdocs directory.

3. Start the Apache server in XAMPP from the XAMPP control panel.

4. Open the website in the browser and access it as: http://<my_domain>/<php_code_folder_name>/index.php
   Here, <my_domain> will be localhost if hosting on your own machine.
   The <php_code_folder_name> is the name of the folder under which the downloaded code resides.

5. Read further instructions when index.php in opened in a browser.


How the code works
------------------

The starting point is "index.php". Click the "PayPal Check out" button (Payment flow 1) or "Proceed to Checkout" button (Payment flow 2) to start a payment flow.  

In the PayPal mini-browser, depending on the flow configuration, you'll see a Continue button (Payment flow 1) or a Pay Now button (Payment flow 2) . If the flow is cancelled, you'll be redirected to "cancel.php".  

"index.php" (Payment Flow 1) and "checkout.php" (Payment Flow 2) do the following:

* When a page is loaded containing client-side code (i.e. index.php and checkout.php), an authorization token is returned by "bt-get-client-token.php".

* When the payment is submitted, the request parameters are posted to "bt-transaction.php".


Review the forms and JavaScript in "index.php" (Payment flow 1) and "checkout.php" (Payment flow 2) to better understand the client configurations.
