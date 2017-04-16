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
		'id' => '1',
		'exclude' => false
	);
	
	$json = $auth->get('trends/place', $params);
	if (!$json) {
		die('Error');
	}
	
	$hashtags = array("", "", "", "", "");
	
	$i = 0;
	$found = 0;
	while ($i < 50 AND $found < 5) {
		if ($json['0']['trends'][$i]['name'][0] == '#') {
			$hashtags[$found] = $json['0']['trends'][$i]['name'];
			$found++;
		}
		$i++;
	}
	if ($found < 5) {
		die('Didn\'t find enough hashtags'
	}
	
	//echo '<pre>'; print_r($json); echo '</pre><hr />';
?>