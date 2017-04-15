<!DOCTYPE html>
<html>
	<head>
		<title> Tweet Street </title>
		<meta charset="UTF-8">
		
		<?php
			//Headers here
			
			
			
			if (!empty($_POST)) {
				if (isset($_POST['pressed_1'])) {
					$pressed_1 = $_POST['pressed_1'];
					
				}
			}
			else {
				$pressed_1 = 0;
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
					if ($pressed_1==1) {
						echo
							'<div class="test">
								Congratulations! You pressed a button!
								
							</div>';
					}
				?>
				<form action="" method="post">
					<input type="submit" value=1 name="pressed_1">
				</form>
			</div>
		</div>
	</body>
</html>