<!DOCTYPE html>
<html>
<head>
  <title>iNi labs Web Push Notification</title>
    <script src="https://www.gstatic.com/firebasejs/7.16.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.16.1/firebase-messaging.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="manifest" href="manifest.json">
<script>
  var config = {
      apiKey: "AIzaSyCtkWE4V2nQuKVF2l2ST4jFuhZCRTWGUOA",
      authDomain: "web-push-eba26.firebaseapp.com",
      databaseURL: "https://web-push-eba26.firebaseio.com",
      projectId: "web-push-eba26",
      storageBucket: "web-push-eba26.appspot.com",
      messagingSenderId: "1068875466225",
      appId: "1:1068875466225:web:e5aa82e64503b2e2d1f050",
      measurementId: "G-LZ511WRGYD"
  };
  firebase.initializeApp(config);

  const messaging = firebase.messaging();
	messaging.requestPermission()
	.then(function() {
	  console.log('Notification permission granted.');
	  // TODO(developer): Retrieve an Instance ID token for use with FCM.
	  if(isTokenSentToServer()) {
	  	console.log('Token already saved.');
	  } else {
	  	getRegToken();
	  }

	})
	.catch(function(err) {
	  console.log('Unable to get permission to notify.', err);
	});

	function getRegToken(argument) {
		messaging.getToken()
		  .then(function(currentToken) {
		    if (currentToken) {
		      saveToken(currentToken);
		      console.log(currentToken);
		      setTokenSentToServer(true);
		    } else {
		      console.log('No Instance ID token available. Request permission to generate one.');
		      setTokenSentToServer(false);
		    }
		  })
		  .catch(function(err) {
		    console.log('An error occurred while retrieving token. ', err);
		    setTokenSentToServer(false);
		  });
	}

	function setTokenSentToServer(sent) {
	    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
	}

	function isTokenSentToServer() {
	    return window.localStorage.getItem('sentToServer') == 1;
	}

	function saveToken(currentToken) {
		$.ajax({
			url: 'action.php',
			method: 'post',
			data: 'token=' + currentToken
		}).done(function(result){
			console.log(result);
		})
	}

	messaging.onMessage(function(payload) {
	  console.log("Message received. ", payload);
	  notificationTitle = payload.data.title;
	  notificationOptions = {
	  	body: payload.data.body,
	  	icon: payload.data.icon,
	  };
	  var notification = new Notification(notificationTitle,notificationOptions);
	});
</script>
</head>
<body>
<center>
    <h2>Web Push Notification </h2>

    <form action="/fcm-push-notification/send.php">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="iNi labs School"><br>
        <label for="description">Description:</label><br>
        <input type="text" id="description" name="description" value="Hello iNilabs School"><br><br>
        <input type="submit" value="Submit">
    </form>
</center>
</body>
