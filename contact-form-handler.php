<?php
require_once('mailer/class.phpmailer.php');
include('mailer/class.smtp.php');

$mail1 = new PHPMailer(true);
$mail1->IsSMTP();

try {
    $mail1->SMTPDebug = 0;
    $mail1->Host = 'wanderlustmarketing.in';
    $mail1->SMTPAuth = true;
    $mail1->Username = 'enquiry@wanderlustmarketing.in';
    $mail1->Password = "Market@2023";
    $mail1->SMTPSecure = 'ssl';
    $mail1->Port = 465;

    $mail1->setFrom('rahuly@parasightsolutions.com', 'Wanderlust Enquiry Form');
    $mail1->addAddress('rahuly@parasightsolutions.com', 'Wanderlust Enquiry Form');

    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    $mail1->isHTML(true);

    $mail1->AddBCC("hrishikeshp@parasightsolutions.com");
    $mail1->AddBCC("enquiry@wanderlustmarketing.in");
    $mail1->AddBCC("prajwalb@parasightsolutions.com");

    $mail1->Subject = 'Wanderlust Enquiry Form';
    $message_data   = "Enquiry Form: " . $name .
        "<br> <html>
  	<head>
  	<style>
  	table,td{
  		border:1px solid #ccc;
  		border-collapse: collapse;
     color:#222;
  	}
  	td{
  		padding:8px;
  		font-size:15px;
  	}
  	</style>
  	</head>
  	<body>
  	<table>
  	<tr>
        <td>Name:</td>
        <td>" . $name . "</td>
    </tr>
  	<tr>
        <td>Email:</td>
        <td>" . $email . "</td>
    </tr>
  	<tr>
        <td>Phone:</td>
        <td>" . $message . "</td>
    </tr>
  	<tr>
        <td>Phone:</td>
        <td>" . $contact . "</td>
    </tr>
  	</table>
  	</body></html>";
    $mail1->Body = $message_data;

    $mail1->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail1->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    if (isset($_POST['recaptcha_response'])) {
        $captcha = $_POST['recaptcha_response'];
    } else {
        $captcha = false;
    }

    if (!$captcha) {
        echo 'Invalid Captcha';
        print "<script>window.location = \"index.html?error=invalid-captcha\"</script>";
    } else {
        $site = '6Lezyw8pAAAAAM_00gPZY3v96Yku4wEuAsIl_Ank';
        $secret = '6Lezyw8pAAAAAAA55iv_5PpDhWkQEa5RK1StT6nF';
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        $response = json_decode($response);

        if ($response->success == false) {
            echo 'Invalid Captcha';
        } else {
            if (isset($_POST['name']) && $name != "") {
                $mail1->send();
                print "<script>window.location = \"success.html\"</script>";
                exit();
            } else {
                echo 'Sorry you have not filled all fields';
            }
        }
    }

    exit();
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail1->ErrorInfo;
}
