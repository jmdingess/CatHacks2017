<?php
			//Establish score variable
			session_start();
			
			if (!isset($_SESSION['score'])){
				$_SESSION['score'] = 0;
			}
			if (!isset($_SESSION['total'])){
				$_SESSION['total'] = 0;
			}
			if (!isset($_SESSION['location'])) {
				$_SESSION['location'] = '23424977'; /*ID For US Region*/
			}
			
			if(!empty($_POST)) {
				if (isset($_POST['correct'])) {
					$_SESSION['total'] = $_SESSION['total'] + 1;
					if ($_POST['correct'] == '1') {
						$_SESSION['score'] = $_SESSION['score'] + 1;
					}
				}
				if (isset($_POST['location'])) {
					$_SESSION['location'] = $_POST['location'];
				}
				if (isset($_POST['reset'])) {
					$_SESSION['score'] = 0;
					$_SESSION['total'] = 0;
				}
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
				'id' => $_SESSION['location'], 
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
				'count' => 100
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
			
			//strip links
			while($pos = strpos($tweetText, 'http')) {
				$length = 0;
				while ($pos + $length < strlen($tweetText) AND $tweetText[$pos+$length] != ' ') {
					$length++;
				}
				$str = "";
				$x = 0;
				while ($x < $pos) {
					$str  = $str . $tweetText[$x];
					$x++;
				}
				$x += $length;
				while ($x < strlen($tweetText)) {
					$str = $str . $tweetText[$x];
					$x++;
				}
				$tweetText = $str;
			}
			//strip ...
			if ($tweetText[strlen($tweetText)-1] == '.' AND $tweetText[strlen($tweetText)-2] == '.' AND $tweetText[strlen($tweetText)-3] == '.' ) {
				$str = "";
				for ($i = 0; $i < strlen($tweetText)-3; $i++) {
					$str = $str . $tweetText[$i];
				}
				$tweetText = $str;
			}
?>