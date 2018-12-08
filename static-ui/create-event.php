<?php require_once ("head-utils.php");?>
<?php require_once ("navbar.php");?>

<main>
	<div class="container">
		<div class="card text-center eventCard mx-auto">
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
					<label for="startDateTime">Event Start Date/Time</label>
					<input type="datetime-local" class="form-control" id="startDateTime" placeholder="select the starting date and time">
				</div>
				<div class="form-group col-8-offset-2">
					<label for="endDateTime">Event End Date/Time</label>
					<input type="datetime-local" class="form-control" id="endDateTime" placeholder="select the expected end date and time">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
	</div>
</main>