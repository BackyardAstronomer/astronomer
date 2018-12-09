import {Status} from "../shared/interfaces/status";
import {EventService} from "../shared/services/event.service";
import {Component, OnInit} from "@angular/core";
import {CommentService} from "../shared/services/comment.service";
import {FormBuilder} from "@angular/forms";
import {ProfileService} from "../shared/services/profile.service";


@Component({
	template: require("./splash.component.html")
})

export class SplashComponent implements OnInit{
	//createCarouselEvent: ;

	status: Status = {status:null, message:null, type:null};

	constructor(private commentService: CommentService, private eventService: EventService, private profileService: ProfileService, private formBuilder: FormBuilder) {}

	ngOnInit() {
	}


}