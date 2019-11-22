import {Component, OnInit} from "@angular/core";
import {EventService} from "../shared/services/event.service";
import {ActivatedRoute} from "@angular/router";
import {Event} from "../shared/interfaces/event";
import {CommentService} from "../shared/services/comment.service";
import {RsvpService} from "../shared/services/rsvp.service";
import {Status} from "../shared/interfaces/status";
import {Rsvp} from "../shared/interfaces/rsvp";

@Component({
	template: require("./event.component.html")
})

export class EventComponent implements OnInit {
eventId : string = this.route.snapshot.params["eventId"];
comments : Comment[];
status : Status = {status : null, message : null, type : null};
event : Event = {eventId: null, eventEventTypeId: null, eventProfileId: null, eventContent: null, eventEndDate: null, eventStartDate: null, eventTitle: null};
constructor(private eventService: EventService, private commentService: CommentService, private route :ActivatedRoute, private  rsvpService: RsvpService){}

ngOnInit() {
	this.eventService.getEventByEventId(this.eventId).subscribe(events => this.event = events);
	this.getComments();
}
getComments() : void{
	this.commentService.getCommentByEventId(this.eventId).subscribe(comment => this.comments = comment)
}

createRsvp() : void{
	let rsvp : Rsvp = {rsvpProfileId : null, rsvpEventId : this.eventId, rsvpEventCounter : null};
	this.rsvpService.postRsvp(rsvp).subscribe(status => this.status = status);
}
}
