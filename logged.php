<?php 
session_start();
?>
<html>
	<head>
		<title>Web Voice</title>
		<script src="website/js/foundation.min.css"></script>
		<link rel="stylesheet" href="website/css/foundation.min.css">
		<link rel="stylesheet" href="website/css/style.css">
		<link rel="stylesheet" href="website/css/app.css">
		<link rel="stylesheet" href="client/css/bootstrap.min.icon.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

        <script src="client/socket.io.js"></script>
        <script src="client/simplewebrtc.bundle.js"></script>
        <script src="website/js/jquery-1.9.1.min.js"></script>
        <script src="website/js/app.js"></script>

        <script>
        	// Initialisation du webrtc
        	var webrtc = new SimpleWebRTC({
                // the id/element dom element that will hold "our" video
                localVideoEl: 'localVideoElement',
                // the id/element dom element that will hold remote videos
                remoteVideosEl: 'remotesVideos',
                // immediately ask for camera access
                autoRequestMedia: true,
                debug: true
            });
        </script>
	</head>

	<body>
		<input type="hidden" value="<?php echo $_SESSION['sessionID']; ?>" id="id">

		<div class="app_container">
			<div id="top_bar">
				<ul>
					<li><a id="state_change"><i class="icon-signal icon-white"></i> State</a></li>
					<li><a onclick="addTab('test', 'id-1', true);"><i class="icon-user icon-white"></i> Profile</a></li>
					<li><a href="index.php"><i class="icon-off icon-white"></i> Disconnect</a></li>
				</ul>

				<ul>
					<li>WebVoice - Cloud powered <i class="icon-globe icon-white"></i></li>
				</ul>
			</div>

			<div id="left_container">
				<div id="contact_list">
					<ul style="margin-top:20px;">
						<li><i class="icon-home"></i> All contacts</li>
						<li><i class="icon-plus-sign"></i> Add a contact</li>
						<li><i class="icon-plus-sign"></i> Create an empty room</li>
					</ul>

					<h1>Contacts</h1>
					<div id="contacts_div">
						
					</div>

					<h1>Last messages</h1>
					<ul>
						<li><i class="icon-comment"></i> Jade Loitier</li>
						<li><i class="icon-random"></i> Eric Szymkowiak</li>
					</ul>

				</div>
				<div id="videoBox">
					<h1><i class="icon-chevron-down"></i> Your webcam</h1>
					<video src="" id="localVideoElement">
						
					</video>
				</div>
			</div>

			<div id="discussion_wrap">
				<ul>
					<li id="tab1">Tab 1</li>
					<li id="tab2">Tab 2</li>
				</ul>

				<div id="tab_contents">
					<div id="tab1-div">
						tab1
					</div>
					<div id="tab2-div">
						tab2
					</div>
				</div>
			</div>
		</div>
	</body>
</html>