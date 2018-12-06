<!DOCTYPE html>
<html>
	<head>
	<?php require_once ("head-utils.php")?>
	<?php require_once("navbar.php")?>

	</head>
	<body>

		<!-- You can now use your library component in app.component.html -->
		<ngx-slick class="carousel" #slickModal="slick-modal" [config]="slideConfig" (afterChange)="afterChange($event)">
			<div ngxSlickItem *ngFor="let slide of slides" class="slide">
				<div class="card" style="width: 18rem;">
					<img src="{{ slide.img }}" alt="" width="100%">
					<div class="card-body">
						<img src="{{star.png}}">
						<h5 class="card-title">Card title</h5>
						<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-primary">Go somewhere</a>
					</div>
				</div>
			</div>
		</ngx-slick>
	</body>
</html>