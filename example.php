<?php

session_start();

$servername = "localhost";  // DB host
$username   = "*****";      // DB username
$password   = "*****";      // DB password
$dbname     = "*******";    // DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

    if($txnType=='CANCEL-REBILL' OR $txnType=='CANCEL-TEST-REBILL'){

        $email = $order->{'customer'}->{'billing'}->{'email'};

        // Write query here

    }else{

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

        $last_id = 1;

        /* ******************************   Example of insert row query  ************************* */

        $sql = "INSERT INTO test_table (first_name, last_name, email, ... ) VALUES ('$fname','$lname','$email', ... )";
        if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }


        /* ******************************   Example of update row query  ************************* */

        $first_name = 'Emon';
        $last_name = 'Ahmed';

        $update_sql_query = "UPDATE test_table SET first_name='$first_name', last_name='$last_name' WHERE id='$last_id'";
        if ($conn->query($update_sql_query) === TRUE) {
        echo "Record updated successfully";
        } else {
        echo "Error: " . $update_sql_query . "<br>" . $conn->error;
        }



        /* ******************************   Example of delete row query  ************************* */

        $delete_sql_query = "DELETE FROM test_table WHERE id='$last_id'";
        if ($conn->query($delete_sql_query) === TRUE) {
        echo "Record deleted";
        } else {
            echo "Error: " . $delete_sql_query . "<br>" . $conn->error;
        }

    }

}

$conn->close();