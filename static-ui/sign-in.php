<?php require_once ("head-utils.php");?>
<?php require_once ("navbar.php");?>

<main>

	<div id="signin" class="container text-center">
		<h1>Sign into Backyard Astronomer!</h1>
		<form class="form-control-lg" id="form" action="" method="post">
			<div class="info">
				<input type="email" class="form-control" id="profileEmail" name="email" placeholder="Email">
				<input type="password" class="form-control" id="profilePassword" name="password" placeholder="Password">
				<input type="password" class="form-control" id="profilePasswordConfirm" name="password-confirm" placeholder="Re-enter Password">
				<br>
				<input class="btn" type="submit" value="Sign In!">
			</div>
		</form>
	</div>
</main>
