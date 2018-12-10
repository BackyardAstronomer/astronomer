import {Component, OnInit} from "@angular/core";
import {EventService} from "../shared/services/event.service";
import {RsvpService} from "../shared/services/rsvp.service";

@Component({
	template: require("./event.component.html")
})

export class EventComponent implements OnInit {


//constructor(private eventService: EventService ){}

ngOnInit() {
	//this.eventService.getEventByProfileId(this.jwtToken.auth.profileId).subscribe(events => this.events = events)
}
}
