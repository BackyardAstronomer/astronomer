import {Component, OnInit} from "@angular/core";
import {EventService} from "../shared/services/event.service";


@Component({
	template: require("./comment.component.html")
})

export class CommentComponent implements OnInit{
	events: Event[];




	constructor(private eventService:EventService){}


	ngOnInit(){
		this.eventService.
	}
}
