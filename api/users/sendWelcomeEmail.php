<?php
include_once '../../utils/ResponseHandler.php';
include_once '../../utils/EmailClient.php';
include_once '../../config/database.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $database = new Database();
    $db = $database->connect();
    $email= $_POST['email'];

    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return;
    }

    $emailSubject = "Welcome To Scriblo!";
    $name = $user['name'];
    $message = `
    <html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Welcome | Scriblo</title>
    <style>
        body {
            font-family: 'Roboto';
            background-color: #fff;
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
			width: 20%;
            margin: 0 auto;
		}

        h1 {
            text-align: center;
        }

        .headerImg {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        button {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            background-color: blue;
            color: white;
            font-weight: bold;
            margin: 0 auto;
            cursor: pointer;
            /* text-align: center; */
        }
        p {
            line-height: 23px;
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

        .footerImg {
            width: 10% !important;
        }

    </style>
</head>
<body>
    <div class='container'>
      <div class="headerImg">
        <img src='https://scriblo.s3.us-east-2.amazonaws.com/branding/brand_logo_black.png' alt='brand logo' />
      </div>
      
      <h1>Welcome To Scriblo 🎉</h1>
      <p>Hey there $name,</p>

<p>Welcome to Scriblo! We're stoked to have you on board as we embark on this creative journey together! 🎉</p>

<p>We're thrilled to have you join our community of passionate writers and bloggers. While Scriblo is still in its development stage, your presence here is a significant milestone for us. Your valuable feedback will play a vital role in shaping the future of this platform.</p>

	<h3>Follow Scriblo's Official Account</h3>
	<p>Be sure to follow Scriblo's official account to catch all the latest news, writing tips, and inspiring stories. It's like subscribing to your favorite blog but even better!</p>
	<a href="http://myscriblo.com/scriblo_19" target="_blank"><button>Follow Scriblo</button></a>

	<h3>🛠️ In Development</h3>
	<p>We want you to know that we're continuously working to enhance your experience on Scriblo. As we iron out the kinks and add exciting features, we encourage you to be an active participant in our journey. Feel free to use the feedback button on any page to share your suggestions or report any bugs you might encounter. Your input is invaluable in helping us build a platform that truly serves your needs.</p>

	<h3>📝 Ready to Create?</h3>
	<p>Now, the fun part! You can start by crafting your very first article. Share your thoughts, stories, and ideas with the world.<br> Let's get those creative juices flowing!</p>
	<a href="http://myscriblo.com/create" target="_blank"><button>Start Writing</button></a>

	<p>Thanks for choosing Scriblo to express yourself. We're genuinely excited to have you here and can't wait to see what incredible content you're going to bring to the table!</p>
	<p>Happy Writing!</p>
	<p>Warmest regards,</p>
	<p style="font-size: 20px;"><b>Levi Okoye <br>
		Founder, Scriblo</b></p>


  <img class="footerImg" src='https://scriblo.s3.us-east-2.amazonaws.com/branding/brandLogo_black.png' alt='brand logo' width='10%' />
  
    </div>
</body>
</html>
    `;

    $emailSent = EmailClient::sendEmail($user['email'], $emailSubject, $message, "hello@myscriblo.com", "Scriblo Team");

    echo ResponseHandler::sendResponse(200, $user['email']);

    // will need only name, email and interests, (maybe anything else)
} else {
    echo ResponseHandler::sendResponse(400, 'Invalid Request');
}