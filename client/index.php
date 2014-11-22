<!DOCTYPE html>
<html>
    <head>
        <title>SimpleWebRTC Demo</title>
        <link rel="stylesheet" href="client/css/custom.css">
        <link rel="stylesheet" href="client/css/bootstrap.min.css">
        <link rel="stylesheet" href="client/css/bootstrap.min.icon.css">
    </head>
    <body>
        <div class="navbar navbar-static-top">
            <div class="container">
              <div class="navbar-inner">
                <a class="navbar-brand" href="#">WebVoice - <span id="room_name"></span></a>
                
                <ul class="nav navbar-nav">
                    <li><a href="#" onclick="">Set up a password</a></li>
                </ul>

                <ul class="nav navbar-nav pull-right">
                  <li><a href="index.php"><i class="icon-remove icon-white"></i> Leave call</a></li>
                </ul>
              </div>
          </div>
        </div>
        
        <div class="container">
            <div class="well">
                You : 
                <button type="button" id="mute" class="btn btn-primary" onclick="toggleMute();">Mute</button>
            </div>

            <section style="margin: 0px;text-align: center;" class="well">
                <h3>Link to join : <span class="link_to_join"></span></h3>
            </section>

            <section>
                <h3>Connected peoples :</h3>

                <div id="connected">
                    
                </div>

                <video id="localVideo" style="height: 150px;">
                    
                </video>

                <div id="remotes">
                    
                </div>
            </section>
        </div>

        <style>
            #remotes video {
                height: 150px;
            }
        </style>

        <p id="subTitle"></p>

        <!-- JS -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="client/socket.io.js"></script>
        <script src="client/simplewebrtc.bundle.js"></script>
        <script>
        function toggleMute() {
            var btn = $("#mute");
            if(btn.hasClass("btn-primary")) {
                btn.removeClass("btn-primary").addClass("btn-danger");
                btn.html("Unmute");
                webrtc.mute();
            } else {
                btn.removeClass("btn-danger").addClass("btn-primary");
                btn.html("Mute");
                webrtc.unmute();
            }
        }

        function getPeerWithId(id) {
            $.each(window.peersArray, function(index, item) {
                if(item.id == id) {
                    return item;
                }
            });
        }
        
        function setRoomPasswd(passwd) {
            webrtc.chgRoomPasswd(passwd);
        }
        
        function actRoomPasswd(bool) {
            webrtc.actRoomPasswd(bool);
        }

        function renderPeers() {
            $("#connected").html("");

            $.each(window.peersArray, function(i, v) {
                $("#connected").append("<p><img src='client/img/micro"+(v.speaking?"":"-off")+".png' id='"+v.id+"-icon'/> "+v.id+"</p>");
            });

            setTimeout("renderPeers();", 100);
        }
            // grab the room from the URL
            //var room = location.search && location.search.split('?')[1];
            var room = "<?php echo htmlentities($_GET['room']); ?>";

            if(room == "") {
                window.location = "/index.php";
            }

            // create our webrtc connection
            var webrtc = new SimpleWebRTC({
                // the id/element dom element that will hold "our" video
                localVideoEl: 'localVideo',
                // the id/element dom element that will hold remote videos
                remoteVideosEl: 'remotes',
                // immediately ask for camera access
                autoRequestMedia: true,
                debug: true
            });

            // when it's ready, join if we got a room from the URL
            webrtc.on('readyToCall', function () {
                // you can name it anything
                if (room) { 
                    webrtc.joinRoom(room) 
                } else {
                    window.location = "/index.php";
                }
                webrtc.stopScreenShare();
                renderPeers();
            });
            
            // Since we use this twice we put it here
            function setRoom(name) {
                $('form').remove();
                $('#room_name').text(name);
                $('.link_to_join').text(location.href);
                $('body').addClass('active');
            }

            if (room) {
                setRoom(room);
            } else {
                $('form').submit(function () {
                    var val = $('#sessionInput').val().toLowerCase().replace(/\s/g, '-').replace(/[^A-Za-z0-9_\-]/g, '');
                    webrtc.createRoom(val, function (err, name) {
                        var newUrl = location.pathname + '?' + name;
                        if (!err) {
                            history.replaceState({foo: 'bar'}, null, newUrl);
                            setRoom(name);
                        }
                    });
                    return false;          
                });
            }

            var button = $('#screenShareButton'),
                setButton = function (bool) {
                    button.text(bool ? 'share screen' : 'stop sharing');
                };

            setButton(true);

            button.click(function () {
                if (webrtc.getLocalScreen()) {
                    webrtc.stopScreenShare();
                    setButton(true);
                } else {
                    webrtc.shareScreen(function (err) {
                        if (err) {
                            setButton(true);
                        } else {
                            setButton(false);
                        }
                    });
                    
                }
            });
        </script>
    </body>
</html>
