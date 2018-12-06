<?php require_once ("head-utils.php");?>
<?php require_once ("navbar.php");?>

<main>

	<div id="create-account" class="container text-center">
		<h1>Sign Up for Backyard Astronomer!</h1>
		<form class="form-control-lg" id="form" action="" method="post">
			<div class="info">
				<input type="text" class="form-control" id="profileName" name="name" placeholder="User Name">
				<input type="email" class="form-control" id="profileEmail" name="email" placeholder="Email">
				<input type="password" class="form-control" id="profilePassword" name="password" placeholder="Password">
				<input type="password" class="form-control" id="profilePasswordConfirm" name="password-confirm" placeholder="Re-enter Password">
				<br>
				<input class="btn" type="submit" value="Sign Up!">
			</div>
		</form>
	</div>

</main>