<!DOCTYPE html>
<html>
	<head>
		<title> Tweet Street </title>
		<meta charset="UTF-8">
		
		<?php
			//Headers here
			
			
			
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
			
			<div class="main-page-content">
				<?php
					if ($pressed == 1) {
						echo
							'<div class="test">
								Button 1 Pressed.
							</div>';
					}
					
					if ($pressed == 2) {
						echo
							'<div class="test">
								Button 2 Pressed.
							</div>';
					}
					
					if ($pressed == 3) {
						echo
							'<div class="test">
								Button 3 Pressed.
							</div>';
					}
					
					if ($pressed == 4) {
						echo
							'<div class="test">
								Button 4 Pressed.	
							</div>';
					}
					
					if ($pressed == 5) {
						echo
							'<div class="test">
								Button 5 Pressed.
							</div>';
					}
				?>
				
				<?php
				echo 
				'<form action="" method="post">
					<input type="submit" value=' . $hashtags[0] . ' name="pressed_1">
					<input type="submit" value=' . $hashtags[1] . ' name="pressed_2">
					<input type="submit" value=' . $hashtags[2] . ' name="pressed_3">
					<input type="submit" value=' . $hashtags[3] . ' name="pressed_4">
					<input type="submit" value=' . $hashtags[4] . ' name="pressed_5">
				</form>'	
				
				?>

			</div>
		</div>
	</body>
</html>