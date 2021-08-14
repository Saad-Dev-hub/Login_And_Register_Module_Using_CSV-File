<nav class="navbar navbar-expand-xl navbar-light bg-light">
	<a href="#" class="navbar-brand"><i class="fa fa-cube"></i>Brand<b>Name</b></a>
	<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
		<span class="navbar-toggler-icon"></span>
	</button>
	<!-- Collection of nav links, forms, and other content for toggling -->
	<div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">
		<div class="navbar-nav">
			<a href="#" class="nav-item nav-link active">Home</a>
			<a href="#" class="nav-item nav-link">About</a>
			<div class="nav-item dropdown">
				<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Services</a>
				<div class="dropdown-menu">
					<a href="#" class="dropdown-item">Web Design</a>
					<a href="#" class="dropdown-item">Web Development</a>
					<a href="#" class="dropdown-item">Graphic Design</a>
					<a href="#" class="dropdown-item">Digital Marketing</a>
				</div>
			</div>
			<a href="#" class="nav-item nav-link">Blog</a>
			<a href="#" class="nav-item nav-link">Contact</a>
		</div>

		<div class="navbar-nav ml-auto">
			<?php if (isset($_SESSION['user'])) { ?>
				<div class="nav-item dropdown">
					<a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle user-action mr-5">
						<!--<img src="/examples/images/avatar/2.jpg" class="avatar">--> <b><?php echo $_SESSION['user'][0]['name'] ?></b> <b class="caret"></b>
					</a>
					<div class="dropdown-menu">
						<!-- <a href="index.php" class="dropdown-item"><i class="fas fa-sign-in-alt"></i>Login</a></a>					 -->
						<a href="Profile.php" class="dropdown-item"><i class="far fa-user"></i> Profile</a></a>
											<a href="#" class="dropdown-item"><i class="fas fa-sliders-h"></i> Settings</a></a>

						<!-- <a href="Sign_up.php" class="dropdown-item"><i class="fas fa-registered"></i> Register</a></a> -->
						<div class="dropdown-divider"></div>
						<a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a></a>
					</div>
				</div>
			<?php } else { ?>
				<div class="nav-item dropdown">
					<a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle user-action mr-5">
						<!--<img src="/examples/images/avatar/2.jpg" class="avatar">--> <b>Welcome</b> <b class="caret"></b>
					</a>
					<div class="dropdown-menu">
						<a href="login.php" class="dropdown-item"><i class="fas fa-sign-in-alt"></i>Login</a></a>					
						<!-- <a href="#" class="dropdown-item"><i class="far fa-user"></i> Profile</a></a> -->
						<a href="SignUp.php" class="dropdown-item"><i class="fas fa-registered"></i> Register</a></a>
						<!-- <div class="dropdown-divider"></div> -->
						<!-- <a href="logout.php" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</a></a> -->
					</div>
				</div>
			<?php }
			?>

		</div>
	</div>
</nav>