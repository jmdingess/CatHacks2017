<!DOCTYPE html>
<html>
	<head>
		<title> Tweet Street </title>
		<meta charset="UTF-8">
		
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
				'id' => '23424977',
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
				die('Didn\'t find enough hashtags');
			}
			
			//for button_presseding, maybe...
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
					Welcome to tweet street!
				</h1>
			</div>
			
			<div class="tweet-box">
				<h1> 
				Guess the hashtag for the following tweet:
				</h1>
				
				<?php
					//echo
					//'<p> '. $tweet .' </p>';
				?>
			</div>
			
			<div class="buttons">
			<button class="button button1">Green</button>
				<?php
				echo 
				'<form action="" method="post">
					<button class = "buttons button1"> <input type="submit" value=' . $hashtags[0] . ' name="pressed_1"> </button>
					<button class = "buttons button2"> <input type="submit" value=' . $hashtags[1] . ' name="pressed_2"> </button>
					<button class = "buttons button3"> <input type="submit" value=' . $hashtags[2] . ' name="pressed_3"> </button>
					<button class = "buttons button4"> <input type="submit" value=' . $hashtags[3] . ' name="pressed_4"> </button>
					<button class = "buttons button5"> <input type="submit" value=' . $hashtags[4] . ' name="pressed_5"> </button>
				</form>';
				
				?>

			</div>
			
			<div class="buttons-pressed">
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