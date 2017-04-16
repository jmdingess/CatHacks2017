<!DOCTYPE html>
<html>
	<head>
		<title> Tweet Street </title>
		<meta charset="UTF-8">
		
		<!-- Javascript headers 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<!-- Latest compiled and minified JavaScript 
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		-->
		<?php
			//Headers here
			
			//Establish score variable
			session_start();
			
			if (!isset($_SESSION['score'])){
				$_SESSION['score'] = 0;
			}
			
				
			
			/* Accessing Twitter API */
			
			if (file_exists(__DIR__ . '/../TwitterOAuth/vendor/autoload.php') ) {
			require __DIR__ . '/../TwitterOAuth/vendor/autoload.php';
			} else {
				die("I was right");
			}

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
			
			$allhashtags = array();
			$allsearchtags = array();
			
			$i = 0;
			$found = 0;
			while ($i < count($json['0']['trends'])) {
				if ($json['0']['trends'][$i]['name'][0] == '#') {
					array_push($allhashtags, $json['0']['trends'][$i]['name']);
					array_push($allsearchtags, $json['0']['trends'][$i]['query']);
					$found++;
				}
				$i++;
			}
			if ($found < 5) {
				die('Didn\'t find enough hashtags');
			}
			
			$hashtags = array();
			$searchtags = array();
			for ($i = 0; $i < 5; $i++) {
				$n = mt_rand(0, $found-1 - $i);
				array_push($hashtags, $allhashtags[$n]);
				array_splice($allhashtags, $n, 1);
				array_push($searchtags, $allsearchtags[$n]);
				array_splice($allsearchtags, $n, 1);
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
			if (count($json['statuses']) == 0) {
				die('Didn\'t get any tweets!');
			}
			
			$tweet = $json['statuses'][mt_rand(0, count($json['statuses'])-1)];
			$tweetText = $tweet['text'];
			
			/*
			foreach (array_reverse($tweet['entities']['hashtags']) as $embeddedhash) {
				if (strtolower($embeddedhash['text']) == strtolower(substr($hashtags[$hashno], 1))) {
					$tweetText = mb_substr($tweetText, 0, $embeddedhash['indices']['0']) . '<b>???</b>' . mb_substr($tweetText, $embeddedhash['indices']['1']);
				}
			}
			*/
			//Delete hashtags
			for ($i = 0; $i < strlen($tweetText); $i++) {
				if ($tweetText[$i] == '#') {
					$match = 1;
					for ($j = 0; $j < strlen($hashtags[$hashno]) AND $i + $j < strlen($tweetText); $j++) {
						if (strtolower($tweetText[$i+$j]) != strtolower($hashtags[$hashno][$j])) {
							$match = 0;
						}
					}
					if ($match == 1) {
						//remove from $tweetText[$i] to $tweetText[$i+strlen($hashno)]
						//  would recommend a for a loop if a function can't do it
						//Then replace it with bolded '???' 
						$str = "";
						$x = 0;
						while ($x < $i) {
							$str  = $str . $tweetText[$x];
							$x++;
						}
						$str = $str . '<b>???</b>';
						$x += $j;
						while ($x < strlen($tweetText)) {
							$str = $str . $tweetText[$x];
							$x++;
						}
						$tweetText = $str;
					}
					
					//$tweetText = substr($tweetText, 0, $i) . '???' . substr($tweetText, $i+$j);
				}
			}
			//$tweetText = str_replace($hashtags[$hashno], '<b>???</b>', $tweetText);
			//echo $tweetText . '<br>' . $tweet['text'];
			//echo '<pre>'; print_r($json); echo '</pre><hr />';
			
			
			
		?>
		
		<!-- css stylesheets -->
		<link rel="stylesheet" href="../css/main_page.css" type="text/css">
		<link rel="stylesheet" href="../css/main.css" type="text/css">
		
	</head>
	
	<body>
		<!--<div id="Overlay" class="overlay">
			
			<form action="" class="overlayform" method="post">
				<button type="submit" class="overlaybutton"> </button>
			</form>
			
		</div> -->
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
					echo '</p>';
				?>
			</div>
			
			<script type="text/javascript">
			function buttonclick(buttonno, checktag, hashtag) {
				if (buttonno - 1 == checktag){
					document.getElementById("ans").innerHTML = "Correct!<br>The right hashtag was " + hashtag + '.<br><a href="">Click here to continue.</a>';
				}
				else {
					document.getElementById("ans").innerHTML = "Incorrect!<br>The right hashtag was " + hashtag + '.<br><a href="">Click here to continue.</a>';
				}
				//document.getElementById("Overlay").style.width = "100%";
				document.getElementById("embeddedtweet").style.display = "inline";
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
			
			<div class="embeddedtweet" id="embeddedtweet">
				<?php
					$params = array(
						'url' => 'https://twitter.com/' . $tweet['user']['screen_name'] . '/status/' . $tweet['id_str'],
						'align' => 'center'
					);
					$json = $auth->get('statuses/oembed', $params);
					echo str_replace('display: block', 'none', $json['html']);
				?>
			</div>
		</div>
	</body>
</html>