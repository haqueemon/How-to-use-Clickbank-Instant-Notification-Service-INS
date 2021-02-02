# Clickbank INS (Instant Notification)     

I'm sharing with you, how Clickbank response their Instant notification(INS) for upsell flows or any type of Instant response from Clickbank.

Clickbank INS response their data in a specific php file. For example : my php file is `ins.php` By below code we can get all INS response data from Clickbank.

`ins.php` :

    <?php
    
    $secretKey = "abcdefghijkl"; // Here you need to put your clickbank secret key
    $message = json_decode(file_get_contents('php://input'));
    $encrypted = $message->{'notification'};
    $iv = $message->{'iv'};

    $decrypted = trim(
        openssl_decrypt(base64_decode($encrypted),
        'AES-256-CBC',
        substr(sha1($secretKey), 0, 32),
        OPENSSL_RAW_DATA,
        base64_decode($iv)), "\0..\32");

    $order = json_decode($decrypted);

    if(empty($order)){
        echo "Order is empty";
    }else{

        $txnType = $order->{'transactionType'};
        $fname = $order->{'customer'}->{'billing'}->{'firstName'};
        $lname = $order->{'customer'}->{'billing'}->{'lastName'};
        $email = $order->{'customer'}->{'billing'}->{'email'};
        $upsellOriginalReceipt = $order->{'upsell'}->{'upsellOriginalReceipt'};
        $upsellSession = $order->{'upsell'}->{'upsellSession'};
        $upsellPath = $order->{'upsell'}->{'upsellPath'};
        $receipt = $order->{'receipt'};
        $vendor = $order->{'vendor'};
        $amount = $order->{'totalAccountAmount'};
        $phone = $order->{'customer'}->{'billing'}->{'phoneNumber'};
        $city = $order->{'customer'}->{'billing'}->{'address'}->{'city'};
        $county = $order->{'customer'}->{'shipping'}->{'address'}->{'county'};
        $state = $order->{'customer'}->{'billing'}->{'address'}->{'state'};
        $postalCode = $order->{'customer'}->{'billing'}->{'address'}->{'postalCode'};
        $country = $order->{'customer'}->{'billing'}->{'address'}->{'country'};
        $txnType = $order->{'transactionType'};

        $item = $order->{'lineItems'}[0]->{'itemNo'};
        $affiliatePayout = $order->{'lineItems'}[0]->{'affiliatePayout'};
        $recurring = $order->{'lineItems'}[0]->{'recurring'};
        $accountAmount = $order->{'lineItems'}[0]->{'accountAmount'};
        $downloadUrl = $order->{'lineItems'}[0]->{'downloadUrl'};
        if($order->{'lineItems'}[1]) {
            $item = $order->{'lineItems'}[1]->{'itemNo'};
            $affiliatePayout = $order->{'lineItems'}[1]->{'affiliatePayout'};
            $recurring = $order->{'lineItems'}[1]->{'recurring'};
            $accountAmount = $order->{'lineItems'}[1]->{'accountAmount'};
            $downloadUrl = $order->{'lineItems'}[1]->{'downloadUrl'};
        }

        $upsellOriginalReceipt = $order->{'upsell'}->{'upsellOriginalReceipt'};
        $upsellSession = $order->{'upsell'}->{'upsellSession'};
        $upsellPath = $order->{'upsell'}->{'upsellPath'};
    }
    
    ?>

> NB : > Check `example.php` to know how to use this response data with row php & mysql database.
