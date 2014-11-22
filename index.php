<?php
/**
* WebVoice logiciel de conversation over-network en javascript
* Ce n'est pas un logiciel libre et il est interdit de recodier et/ou de vendre une partie de ce logiciel
* sans l'accord de l'auteur de la partie spécifique du code
* Rajoute ton nom après avoir effectuer des modifications
* 	Codeurs:
*		- Théo Szymkowiak
*/
?>
<html>
	<head>
		<title>WebVoice - Talk for free all around the world</title>
		<script src="website/js/foundation.min.css"></script>
		<link rel="stylesheet" href="website/css/foundation.min.css">
		<link rel="stylesheet" href="website/css/style.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

        <script>
        	function createRoom() {
        		var name = $("#room_name").val();

        		if(name == "" || name.length > 50) {
        			$("#room_name").addClass("error");

        			$("#errors").html("The name could not be empty or too long (>50 caracters).").css("display", "block");
        			return;
        		}

        		name = name.replace(" ", "-");
        		window.location = name+".room";
        	}
        </script>
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

			<section style="margin: 50px;">
				<div class="large-4 columns">
					<img src="website/img/cloud.png" alt="">
				</div>
				<div class="large-8 columns">
					<h2>Try out now without any account !</h2>

				  <div class="row collapse">
			        <div class="small-9 columns">
			          <input type="text" id="room_name" placeholder="Name of your room">
      				  <small class="error" style="display:none;" id="errors"></small>
			        </div>
			        <div class="small-3 columns">
			          <span class="postfix radius"><a onclick="createRoom();">Create this room</a></span>
			        </div>
			      </div>

			      <p style="color:grey;font-style: italic;">Note : If the room already exist you will automaticly join it.</p>
				</div>
			</section>

			<hr style="margin-top:240px"/>
			<h2 style="text-align:center;margin-top:50px;">No software. No plugins. No paiements. <small>No flash :D</small></h2>

			<div class="row" style="text-align:center;margin: 50px 0px 40px 0px;">
				<div class="large-3 columns">
					<h3>Microphone Chat</h3>
					<p>Easily chat with other people with your microphone</p>
				</div>
				<div class="large-3 columns">
					<h3>Video Chat</h3>
					<p>You can activate the video at any time</p>
				</div>
				<div class="large-3 columns">
					<h3>Group Chat</h3>
					<p>You can add up to 6 person to the chat</p>
				</div>
				<div class="large-3 columns">
					<h3>Locked Room</h3>
					<p>Easily set a password to join your conversation</p>
				</div>
			</div>

			<section id="images_nav">
				<h2 style="margin: 30px 0px 40px 0px;">Works with :</h2>
				<div class="row">
					<img src="website/img/ff.png" alt="">
					<img src="website/img/chrome.jpg" alt="">
					<img src="website/img/not-ie.jpg" alt="">
				</div>
				<p style="margin-top: 10px;color:grey;font-style: italic;">Not Firefox yet. Google Chrome. Not Internet Explorer</p>
			</section>

			<hr style="margin-top:50px"/>

			<section style="text-align:center;">
				<h3>Create an account</h3>
				<p>With this account you can create a list of contact and call them.</p>
				<a href="register.php" class="button" style="margin: 10px 0px 50px 0px;">
					Register now
				</a>
			</section>

			<hr/>
			
			<footer>
				<ul>
					<li><a href="mailto:">Contact</a></li>
					<li><a href="http://theoszymko.free.fr/">Creator</a></li>
				</ul>
			</footer>
		</div>
	</body>
</html>