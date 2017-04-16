<!DOCTYPE html>
<html>
	<head>
		<title> Tweet Street </title>
		<meta charset="UTF-8">
		
		<!-- Javascript headers -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		
		<?php
			//Headers here
			
			
			
			
			/* Accessing Twitter API */
	
			require __DIR__ . '/../TwitterOAuth/vendor/autoload.php';

			use TwitterOAuth\Auth\SingleUserAuth;

			/**
			 * Serializer Namespace
			 */
			use TwitterOAuth\Serializer\ArraySerializer;


			date_default_timezone_set('UTC');

			/**
			 * Array with the OAuth tokens provided by Twitter
			 *   - consumer_key        Twitter API key
			 *   - consumer_secret     Twitter API secret
			 *   - oauth_token         Twitter Access token         * Optional For GET Calls
			 *   - oauth_token_secret  Twitter Access token secret  * Optional For GET Calls
			 */
			$credentials = array(
				'consumer_key' => 'A7CEdR0AdxsUO5IrOjHE8ZD93',
				'consumer_secret' => '5REJuDl6s6nAn59vmoB4cKCETEewbBebO3psyrVAm8i3dcqEsf',
				'oauth_token' => '853369130686263299-zDkaWA2y4TDXbgQCDQx6DgI4LItnvUl',
				'oauth_token_secret' => 'L7ruVVtKEcfALBqRPE0azdoEOFcTgFgqcmSANWZPD0D0a',
			);
			
			/**
			 * Instantiate SingleUser
			 *
			 * For different output formats you can set one of available serializers
			 * (Array, Json, Object, Text or a custom one)
			 */
			$auth = new SingleUserAuth($credentials, new ArraySerializer());
			
			
			$params = array(
				'id' => '23424977', /*ID For US Region*/
				'exclude' => false
			);
			
			$json = $auth->get('trends/place', $params);
			if (!$json) {
				die('Error');
			}
			
			$hashtags = array("", "", "", "", "");
			$searchtags = array("", "", "", "", "");
			
			$i = 0;
			$found = 0;
			while ($i < 50 AND $found < 5) {
				if ($json['0']['trends'][$i]['name'][0] == '#') {
					$hashtags[$found] = $json['0']['trends'][$i]['name'];
					$searchtags[$found] = $json['0']['trends'][$i]['query'];
					$found++;
				}
				$i++;
			}
			if ($found < 5) {
				die('Didn\'t find enough hashtags');
			}
			
			$hashno = mt_rand(0, 4);
			
			$params = array(
				'q' => $searchtags[$hashno],
				'count' => 50
			);
			
			$json = $auth->get('search/tweets', $params);
			if (!$json) {
				die('Error getting tweet');
			}
			if (count($json['statuses']) < 50) {
				die('Didn\'t get enough tweets! Got ' . count($json['statuses']) . ' and expected 50!');
			}
			
			$tweet = $json['statuses'][mt_rand(0, 49)];
			$tweetText = $tweet['text'];
			
			
			foreach (array_reverse($tweet['entities']['hashtags']) as $embeddedhash) {
				if (strtolower($embeddedhash['text']) == strtolower(substr($hashtags[$hashno], 1))) {
					$tweetText = substr($tweetText, 0, $embeddedhash['indices']['0']) . '<b>???</b>' . substr($tweetText, $embeddedhash['indices']['1']);
				}
			}
			//Delete all hashtags
			/*
			for ($i = 0; $i < strlen($tweetText); $i++) {
				if ($tweetText[$i] == '#') {
					$j = 0;
					while ($i+$j < strlen($tweetText) AND $tweetText[$i+$j] != ' ') {
						$j++;
					}
					$tweetText = substr($tweetText, 0, $i) . '???' . substr($tweetText, $i+$j);
				}
			}*/
			//echo $tweetText . '<br>' . $tweet['text'];
			//echo '<pre>'; print_r($json); echo '</pre><hr />';
			
			
			if (!empty($_POST)) {
				if (isset($_POST['pressed_1'])) {
					$pressed = 1;
					
				}
				
				if (isset($_POST['pressed_2'])) {
					$pressed = 2;
					
				}

				if (isset($_POST['pressed_3'])) {
					$pressed = 3;
					
				}
				
				if (isset($_POST['pressed_4'])) {
					$pressed = 4;
					
				}
				
				if (isset ($_POST['pressed_5'])) {
					$pressed = 5;
				}
			}
			else {
				$pressed = 0;

			}
		?>
		
		<!-- css stylesheets -->
		<link rel="stylesheet" href="../css/main_page.css" type="text/css">
		<link rel="stylesheet" href="../css/main.css" type="text/css">
		
	</head>
	
	<body>
		<div id="main-page" class="container">
			<div class="main-page-title">
				<h1>
					Welcome to Tweet Street!
				</h1>
			</div>
			
			<div class="tweet-box">
				<h1> 
				Guess the hashtag for the following tweet:
				</h1>
				
				<?php
					echo '<p> '. $tweetText . '<br>';
					if (array_key_exists('media', $tweet['entities'])) {
						foreach ($tweet['entities']['media'] as $embeddedmedia) {
							if ($embeddedmedia['type'] == 'photo') {
								echo 
								'<img src="' . $embeddedmedia['media_url_https'] . '"><br>';
							}
						}
					}
					echo '<a href="https://twitter.com/' . $tweet['user']['screen_name'] . '/status/' . $tweet['id_str'] . '" target=_blank> Original </a> </p>';
				?>
			</div>
			
			<script type="text/javascript">
			function buttonclick(buttonno, checktag, hashtag) {
				if (buttonno - 1 == checktag){
					document.getElementById("ans").innerHTML = "Correct!<br>The right hashtag was " + hashtag + ".<br>Click anywhere to continue.";
				}
				else {
					document.getElementById("ans").innerHTML = "Incorrect!<br>The right hashtag was " + hashtag + ".<br>Click anywhere to continue.";
				}
				
			}
			</script>
			
			<div class="buttons">
				<?php
				echo '
				<button class = "button button1" onclick="buttonclick(1, ' . $hashno . ', \'' . $hashtags[$hashno] . '\')" name="pressed_1"> ' . $hashtags[0] . ' </button>
				<button class = "button button2" onclick="buttonclick(2, ' . $hashno . ', \'' . $hashtags[$hashno] . '\')" name="pressed_2"> ' . $hashtags[1] . ' </button>
				<button class = "button button3" onclick="buttonclick(3, ' . $hashno . ', \'' . $hashtags[$hashno] . '\')" name="pressed_3"> ' . $hashtags[2] . ' </button>
				<button class = "button button4" onclick="buttonclick(4, ' . $hashno . ', \'' . $hashtags[$hashno] . '\')" name="pressed_4"> ' . $hashtags[3] . ' </button>
				<button class = "button button5" onclick="buttonclick(5, ' . $hashno . ', \'' . $hashtags[$hashno] . '\')" name="pressed_5"> ' . $hashtags[4] . ' </button>
				';
				
				?>
			</div>
			
			<p class="button-pressed" id="ans"></p>
			
			<div class="button-pressed">
				<?php
				
					if ($pressed == 1) {
						echo
							'Button 1 Pressed.';
					}
					
					if ($pressed == 2) {
						echo
							'Button 2 Pressed.';
					}
					
					if ($pressed == 3) {
						echo
							'Button 3 Pressed.';
					}
					
					if ($pressed == 4) {
						echo
							'Button 4 Pressed.';
					}
					
					if ($pressed == 5) {
						echo
							'Button 5 Pressed.';
					}
				?>
			</div>
			
		</div>
	</body>
</html>