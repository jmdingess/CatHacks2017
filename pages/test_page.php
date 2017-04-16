<?php
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
		'id' => '23424977',
		'exclude' => false
	);
	
	$json = $auth->get('trends/place', $params);
	if (!$json) {
		die('Error getting hashtags');
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
	
	
	
	//Deletes all hashtags
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
	
	
	
	echo $tweetText . '<br>' . $tweet['text'];
	echo '<pre>'; print_r($json); echo '</pre><hr />';
?>