import {Status} from "../shared/interfaces/status";
import {EventService} from "../shared/services/event.service";
import {Component, OnInit} from "@angular/core";
import {EventUser} from "../shared/interfaces/event-user";


@Component({
	template: require("./splash.component.html")
})

export class SplashComponent implements OnInit{
	//createCarouselEvent: ;
	//events: EventUser[];
	//status: Status = {status:null, message:null, type:null};

	//constructor(//private eventService: EventService) {}

	ngOnInit() {
		//this.eventService.getAllEvents().subscribe(events => this.events = events)
	}
}