<?php require_once ("head-utils.php");
	require_once ("navbar.php");?>
<main>
	<div class="container text-center">
		<h1>Create Event</h1>
		<div class="row justify-content-center">
			<form>
				<div class="form-group col-8-offset-2">
					<input type="text" class="form-control" id="eventTitle" placeholder="Title of event.">
				</div>
				<div class="form-group col-8-offset-2">
					<textarea rows="5" class="form-control" id="eventContent" placeholder="Write a short description of the event here."></textarea>
				</div>
				<div class="form-group col-8-offset-2">
					<input type="datetime-local" class="form-control" id="startDateTime" placeholder="select the starting date and time">
				</div>
				<div class="form-group col-8-offset-2">
					<input type="datetime-local" class="form-control" id="endDateTime" placeholder="select the expected end date and time">
				</div>
			</form>
		</div>
	</div>
</main>
