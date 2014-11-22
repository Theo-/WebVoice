<?php 
session_start();
include 'lib/Db-auth.php';
include 'lib/Form.php';
include 'lib/Secure.php';
include 'lib/Db.php';

$login = new Form('login', array(
	"email" => array(
		'required' => array(true, 'You should provide an email.')
	),
	"psw" => array(
		'required' => array(true, 'You should provide a password.')
	)
));

if($login->isValid()) {
	$db = basePDO();
	$email = Secure::html(Form::getPost("email"));
	$psw = Secure::password(Form::getPost("psw"));

	$user = $db->select("* FROM users WHERE email='$email' AND psw='$psw'")->one();

	if(!$user) {
		$login->addError("There no account matching this email and this password.");
	} else {
		$token = md5(uniqid(rand(), true));
		$userid = $user->id;
		$db->update("users SET token='$token' WHERE id=$userid");

		$_SESSION['sessionID'] = $userid;
		$_SESSION['token'] = $token;

		echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL=logged.php">';
 		//header("Location: logged.php"); // Does not work, see meta tag below
		exit(0); // Quit, send the meta tag
	}
}
?>
<html>
	<head>
		<title>WebVoice - Talk for free all around the world - Login</title>
		<script src="website/js/foundation.min.css"></script>
		<link rel="stylesheet" href="website/css/foundation.min.css">
		<link rel="stylesheet" href="website/css/style.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

        <style>
			#errors li {
				list-style-type: none;
			}
        </style>
	</head>
	<body>
		<div id="cloudfont">
			
		</div>

		<div class="row">
			<section style="text-align:center;width:300px;margin:auto;">
				<div class="panel">
					<h1>WebVoice</h1>
					<p style="color:red;">
			<?php 
			echo $login->listErrors("errors");
			?></p>

					<form action="#" method="post">
						<label for="email">Email</label>
						<input type="text" name="email" id="email">
						<label for="psw">Password</label>
						<input type="password" name="psw" id="psw">

						<input type="submit" class="button expand round" name="login" value="Login">
					</form>
				</div>
			</section>
		</div>
	</body>
</html>
