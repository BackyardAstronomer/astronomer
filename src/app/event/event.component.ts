import {Component, OnInit} from "@angular/core";
import {EventService} from "../shared/services/event.service";
import {ActivatedRoute} from "@angular/router";
import {Event} from "../shared/interfaces/event";

@Component({
	template: require("./event.component.html")
})

export class EventComponent implements OnInit {
eventId : string =	 this.route.snapshot.params["eventId"];
event : Event = {eventId: null, eventEventTypeId: null, eventProfileId: null, eventContent: null, eventEndDate: null, eventStartDate: null, eventTitle: null};
constructor(private eventService: EventService, private route :ActivatedRoute ){}

ngOnInit() {
	this.eventService.getEventByEventId(this.eventId).subscribe(events => this.event = events)
}
}
