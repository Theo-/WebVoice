<?php 
include 'lib/Db-auth.php';
include 'lib/Form.php';
include 'lib/Db.php';
include 'lib/Secure.php';

$registerForm = new Form('register',array(
	"display" => array(
		'required' => array(true, 'Display name required.')
	),
	"email" => array(
		'required' => array(true, 'A email is required to contact you.')
	),
	"psw" => array(
		'required' => array(true, 'For your security a password is required.')
	),
	"psw2" => array(
		'equal' => array($_POST['psw'], 'The second password do not match the first one.')
	)
));

if($registerForm->isValid()) {
	$db = basePDO();

	$email = Secure::html($_POST['email']);

	$psw = Secure::password($_POST['psw']);
	$countEmail = $db->select("* FROM users WHERE email='$email'")->one();

	if(!$countEmail) {

		$db->insert("users", array(
			"display" => html_entity_decode($_POST['display']),
			"email" => Secure::html($_POST['email']),
			"psw" => $psw,
			"level" => "1"
		));

		$logGreen = "You have been successfuly registered. You can now <a href='login.php'>log in</a> and start chating.";

	} else {
		$registerForm->addError("This email is already taken.");
	}
}

?><html>
	<head>
		<title>WebVoice - Talk for free all around the world - Register</title>
		<script src="website/js/foundation.min.css"></script>
		<link rel="stylesheet" href="website/css/foundation.min.css">
		<link rel="stylesheet" href="website/css/style.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	</head>
	<body>
		<div id="cloudfont">
			
		</div>
	
		<div class="row" style="text-align:right;">
			<a href="register.php" class="button secondary small round">Register</a>
			<a href="login.php" class="button small round">Login</a>
		</div>

		<div class="row">
			<section style="margin-bottom:40px;text-align:center;">
				<img src="website/img/webvoice.png" style="margin: 0px 0px 30px 0px;" alt="Web Voice Logo">
				<h5>Use the cloud to stay connected with your family.</h5>
			</section>

			<hr/>

			<section>
				<h1>Register</h1>
				<p>Sign-up now to use tomorrow's new technologies.</p>
				
				<div style="width: 300px;margin: 30px auto 30px auto;">
					<p style="color:red;">
					<?php 
					echo $registerForm->listErrors("errors");
					?></p>
					<form action="#" method="post">
						<label for="">Display name:</label>
						<input type="text" name="display">
						<label for="">Email:</label>
						<input type="text" name="email">
						<label for="">Password:</label>
						<input type="password" name="psw">
						<label for="">Retype password:</label>
						<input type="password" name="psw2">

						<input type="submit" name="register" value="Sign-up" class="button">
					</form>
				</div>
			</section>
		</div>
	</body>
</html>
