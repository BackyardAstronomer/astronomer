import {Component, OnInit} from "@angular/core";
import {EventService} from "../shared/services/event.service";
import {ActivatedRoute} from "@angular/router";
import {Event} from "../shared/interfaces/event";
import {CommentService} from "../shared/services/comment.service";

@Component({
	template: require("./event.component.html")
})

export class EventComponent implements OnInit {
eventId : string =	 this.route.snapshot.params["eventId"];
comments : Comment[];
event : Event = {eventId: null, eventEventTypeId: null, eventProfileId: null, eventContent: null, eventEndDate: null, eventStartDate: null, eventTitle: null};
constructor(private eventService: EventService, private commentService: CommentService, private route :ActivatedRoute ){}

ngOnInit() {
	this.eventService.getEventByEventId(this.eventId).subscribe(events => this.event = events);
	this.getComments();
}
getComments() : void{
	this.commentService.getCommentByEventId(this.eventId).subscribe(comment => this.comments = comment)
}
}
