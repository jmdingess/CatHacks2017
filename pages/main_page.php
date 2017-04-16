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
		
		<?php include 'header.php';?>
		
		<!-- css stylesheets -->
		<link rel="stylesheet" href="../css/main_page.css" type="text/css">
		<link rel="stylesheet" href="../css/main.css" type="text/css">
		
		<!-- favicon -->
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	</head>
	
	<body>
		<div id="main-page" class="container">
			<div class="flags">
				<form action="" method="post">
				<?php
					echo '
					<button class = "flag" type="submit" value="23424977" name="location">United States</button>
					<button class = "flag" type="submit" value="23424936" name="location">Russia</button>
					<button class = "flag" type="submit" value="23424829" name="location">Germany</button>
					<button class = "flag" type="submit" value="23424975" name="location">United Kingdom</button>
					<button class = "flag" type="submit" value="23424856" name="location">Japan</button>	
					<button class = "flag" type="submit" value="23424848" name="location">India</button>
					<button class = "flag" type="submit" value="23424768" name="location">Brazil</button>
					<button class = "flag" type="submit" value="23424908" name="location">Nigeria</button>	
					<button class = "flag" type="submit" value="23424748" name="location">Australia</button>
					';
					?>
				</form>
			</div>
			
			<div class="main-page-title">
				<h1>
					Welcome to Tweet Street!
				</h1>
			</div>
			
			<div class="stat-box">
				<form action="" method="post">
					<?php
						echo '<p>Correct: ' . $_SESSION['score'] . ' •  Total: ' . $_SESSION['total'] . '</p>
							<button class = "flag" type="submit" value="" name="reset">Reset Scores</button>';
					?>
				</form>
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
				if (document.getElementById("ans").innerHTML == "") {
					if (buttonno - 1 == checktag){
						document.getElementById("ans").innerHTML = "Correct!<br>The right hashtag was " + hashtag + '.<br><form method="POST" action="">  <button type="submit" name="correct" value="1" class="button continuebutton"> Click here to continue. </button> </form>';
					}
					else {
						document.getElementById("ans").innerHTML = "Incorrect!<br>The right hashtag was " + hashtag + '.<br><form method="POST" action="">  <button type="submit" name="correct" value="0" class="button continuebutton"> Click here to continue. </button> </form>';
					}
					document.getElementById("embeddedtweet").style.display = "inline";
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
		<br><br><br>
	</body>
</html>