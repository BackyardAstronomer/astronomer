import {EventService} from "../shared/services/event.service";
import {Component, OnInit} from "@angular/core";
import {EventUser} from "../shared/interfaces/event-user";




@Component({
	template: require("./splash.component.html")
})

export class SplashComponent implements OnInit{

	eventUser: EventUser[];

	constructor( private eventService: EventService) {}

	ngOnInit() {
		this.getUsers()
	}

	getUsers() {
		this.eventService.getAllEvents().subscribe(events => this.eventUser = events);
	}
	slides = [
		{img: "http://placehold.it/350x150/000000"},
		{img: "http://placehold.it/350x150/111111"},
		{img: "http://placehold.it/350x150/333333"},
		{img: "http://placehold.it/350x150/666666"},
		{img: "http://placehold.it/350x150/000000"},
		{img: "http://placehold.it/350x150/111111"},
		{img: "http://placehold.it/350x150/333333"},
		{img: "http://placehold.it/350x150/666666"}
	];

	slideConfig = {"slidesToShow": 2, "slidesToScroll": 1};

	afterChange(e) {
		console.log('afterChange');
	}
}