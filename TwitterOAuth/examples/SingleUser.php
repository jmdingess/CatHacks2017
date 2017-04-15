<?php

/**
 * TwitterOAuth - https://github.com/ricardoper/TwitterOAuth
 * PHP library to communicate with Twitter OAuth API version 1.1
 *
 * @author Ricardo Pereira <github@ricardopereira.es>
 * @copyright 2014
 */

require __DIR__ . '/../vendor/autoload.php';

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


/**
 * Returns a collection of the most recent Tweets posted by the user
 * https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
 */
$params = array(
    'screen_name' => 'ricard0per',
    'count' => 3,
    'exclude_replies' => true
);

/**
 * Send a GET call with set parameters
 */
$response = $auth->get('statuses/user_timeline', $params);

echo '<pre>'; print_r($auth->getHeaders()); echo '</pre>';

echo '<pre>'; print_r($response); echo '</pre><hr />';
