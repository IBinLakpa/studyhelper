<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $title;?></title>
		<base href="/">
		<meta property="og:site_name" content="Notes Archive">
		<link rel="stylesheet" href="styles/default.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		
		<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
		<script 
			type="text/javascript"
			src="scripts/accordion.js"
		>
		</script>
		<?php
			if($banner){
			    echo '<link rel="stylesheet" href="styles/banner.css"/>';
			}
			if($form){
			    echo '
					<link rel="stylesheet" href="styles/form.css"/>
					<script 
						type="text/javascript"
						src="scripts/form_ajax.js"
					>
					</script>
				';
			}
			if($editor){
				echo '
					<link rel="stylesheet" href="styles/editor.css"/>
					<script 
						type="text/javascript"
						src="scripts/editor_toolbar.js"
					>
					</script>
				';
			}
			?>
	</head>
	<body 
		<?php
			if($form){ echo "class='form'";}
		?>
	>
		<header>
			<a href="#home">
			[Logo]
			</a>
			<div class="menuToggle"></div>
			<nav>
				<ul>
					<li>
						<a href="category/all">Categories</a>
					</li>
					<li>
						<a href="institute/all">Institutes</a>
					</li>
					<li>
						<?php
							if($user_id){
								echo "<a href='user/".$user_id."'>Status</a>";
							}
							else{
								echo '<a href="user/signup">Sign up / Login</a>';
							}
						?>
					</li>
				</ul>
			</nav>
		</header>
		<?php
			echo $left_sidebar_options;
			echo main_article();
			echo $right_sidebar_options;
		?>
		<footer>
			[footer WIP]
		</footer>
	</body>
</html>