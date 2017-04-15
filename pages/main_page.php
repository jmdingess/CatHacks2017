<!DOCTYPE html>
<html>
	<head>
		<title> Suck my dick </title>
		<meta charset="UTF-8">
		
		<?php
			//Headers here
			
			
			
			if (!empty($_POST)) {
				if (isset($_POST['meme'])) {
					$meme = $_POST['meme'];
					
				}
			}
			else {
				$meme = 0;
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
					Suck My Donkey Dong
				</h1>
			</div>
			<div class="main-page-content">
				<?php
					if ($meme==1) {
						echo
							'<div class="fuck-you">
								Fuck you
								
							</div>';
					}
				?>
				<form action="" method="post">
					<input type="submit" value=1 name="meme">
				</form>
			</div>
		</div>
	</body>
</html>