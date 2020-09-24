<?php 
	define('SERVER_API_KEY', 'AAAA-N3xRfE:APA91bEcW_vBPzF9nOqng9huuovkRBi7JSOh1CLQN71ep8mQV-nnF9RNyhxAAcDDIFPv4nkcyl9_-2Zf6XKUgV_KzjCwRgp5q994vv428U-y_SWftIJCkIEl35zMHNl9HqzBDQBS6qvX');

	require 'DbConnect.php';
	$db = new DbConnect;
	$conn = $db->connect();
	$stmt = $conn->prepare('SELECT * FROM tokens');
	$stmt->execute();
	$tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($tokens as $token) {
		$registrationIds[] = $token['token'];
	}

	$header = [
		'Authorization: Key=' . SERVER_API_KEY,
		'Content-Type: Application/json'
	];

	$msg = [
		'title' => 'iNilabs School',
		'body' => 'Hello iNi labs School',
		'icon' => 'img/icon.png',
	];

	$payload = [
		'registration_ids' 	=> $registrationIds,
		'data'				=> $msg
	];

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => json_encode( $payload ),
	  CURLOPT_HTTPHEADER => $header
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
		header("Location: http://localhost/fcm-push-notification/");
	}
 ?>
