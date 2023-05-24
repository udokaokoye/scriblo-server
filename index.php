<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include 'connection.php';
include './ResponseHandler.php';
include './emailclient.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailMessage = "
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
        h1 {
            color: #333;
        }
        p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
      <img src='https://scriblo.s3.us-east-2.amazonaws.com/branding/Vector.png' alt='brand logo' />
    <h3>Hey There 👋 thank You for Joining the Waitlist! </h3>
      
        <p>We appreciate your interest in our application. You are now on our waitlist, and we're excited to have you onboard. Be the first to experience our powerful blogging platform that empowers writers like you to share their ideas with the world.</p>
      
      <p>As a valued member of our community, you'll receive exclusive updates, early access, and special offers. We can't wait to show you what we've been working on!</p>
      
      <p> Thank you again for joining the waitlist. We're thrilled to have you as part of our journey! </p>
      
      <p>Best regards, </br>
Scriblo Team</p>
  <img src='https://scriblo.s3.us-east-2.amazonaws.com/branding/brandLogo_black.png' alt='brand logo' width='10%' />
  
    </div>
</body>
</html>
    ";
        $preEmail = $_POST['email'];
        $query = "SELECT * FROM `waitlist` WHERE `email` = '$preEmail' ";
        $result = $conn->query($query);
        // get number of rows
        $num_rows = $result->num_rows;
        if ($num_rows > 0) {
            ResponseHandler::sendResponse(400, "Email already exists");
            exit();
        }

    $query = "INSERT INTO `waitlist` (`email`) VALUES (?) "; // ? is a placeholder
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // "s" - string, "i" - int, "d" - double, "b" - blob
    $email = $_POST['email'];
    if ($email == null || $email == "") {
        ResponseHandler::sendResponse(400, "Email cannot be empty");
        exit();
    }
    $stmt->execute();
    // cleck if successfully inserted
    if ($stmt) {
        ResponseHandler::sendResponse(200, "Successfully inserted");
        $emailSent = EmailSender::sendEmail($email, "Thanks For Joining Our Waitlist! 🎉", $emailMessage, "hello@myscriblo.com", "Scriblo Team");
        

    } else {
        ResponseHandler::sendResponse(500, "Error inserting");
    }
    $stmt->close();
} else {
    echo "Request method not accepted";
}