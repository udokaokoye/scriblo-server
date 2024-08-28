<?php
include_once '../../config/database.php';
include_once '../../models/User.php';
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/TokenGenerator.php';
include_once '../../utils/EmailClient.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // echo json_encode(['message' => 'User exists']);
    // return;
    if (!isset($_POST['email'])) {
        echo ResponseHandler::sendResponse(400, 'Email not provided');
        return;
    }
    $database = new Database();
    $db = $database->connect();
    $token = TokenGenerator::generateToken();
    // $token = 12345;
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $currentDateTime = new DateTime();
    $tokenMessage = "
    <html>
<head>
    <title>Welcome | Scriblo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
		.container img {
			margin: 0 auto;
		}
		.code {
		    font-weight: bolder;
			font-size: 35px;
			text-align: center;
			letter-spacing: 10px;
		}
        h1 {
            color: #333;
        }

    </style>
</head>
<body>
    <div class='container'>
      <img src='https://scriblo.s3.us-east-2.amazonaws.com/branding/brand_logo_black.png' alt='brand logo' />
    <h3 style='text-align: center'>Hey There 👋 Thank You for Choosing Scriblo! </h3>
      
        <p>We are excited to have you on board. To ensure the security of your account, we require you to complete the verification process by confirming your email address.</p>
			<p class='code'>$token<p>
      <p>Please note that the token is valid for a limited time only. If you don't verify your email within 15 minutes, you may need to request a new token.

				If you did not create an account on our platform, please ignore this email. Rest assured, your information remains safe.
				
				</p>
      
      <p> Thank you again for joining scriblo. We're thrilled to have you as part of our journey! </p>
      
      <p>Best regards, </br>
Scriblo Team</p>
  <img src='https://scriblo.s3.us-east-2.amazonaws.com/branding/brandLogo_black.png' alt='brand logo' width='10%' />
  
    </div>
</body>
</html>
    ";

    // check if there is already a token for this user
    $query = 'SELECT token FROM tokens WHERE email = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$_POST['email']]);
    if ($stmt->rowCount() > 0) {
        // delete the token
        $query = 'DELETE FROM tokens WHERE email = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$_POST['email']]);
    }

    

    // Add 10 minutes to the current date and time
    $expirationDateTime = $currentDateTime->add(new DateInterval('PT10M'));

    // Format the expiration date and time
    $expirationDate = $expirationDateTime->format('Y-m-d H:i:s');

    $query = 'INSERT INTO tokens (token, email, expires) VALUES (?, ?, ?)';
    $stmt = $db->prepare($query);
    $stmt->execute([$hashedToken, $_POST['email'], $expirationDate]); // $_POST['email'] is the email of the user who is requesting a token
    if ($stmt) {
        $emailSent = EmailClient::sendEmail($_POST['email'], 'Scriblo - Your 5 digit verification token', $tokenMessage, 'hello@scriblo.com', 'Scriblo');
        if ($emailSent) {
            echo ResponseHandler::sendResponse(200, 'Token sent');
            return;
        } else {
            echo ResponseHandler::sendResponse(500, 'Error sending email');
            return;
        }
    } else {
        echo ResponseHandler::sendResponse(500, 'Error generating token');
        return;
    }

} else {
    echo json_encode(['error' => 'Method not allowed']);
}
