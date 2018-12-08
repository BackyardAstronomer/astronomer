import {Component, OnInit} from "@angular/core";
import {CommentService} from "../shared/services/comment.service";
import {EventService} from "../shared/services/event.service";
import {ProfileService} from "../shared/services/profile.service";


@Component({
	template: require("./profile.component.html")
})

export class ProfileComponent implements OnInit{
	profiles: Profiles[];
	events: Events[];
	status: Status = {status:null, message:null, type:null};

	constructor(private commentService: CommentService, private eventService: EventService, private profileService: ProfileService) {}

	ngOnInit() {
		this.profileService.getProfileByProfileId().subscribe(profiles => this.profiles = profiles);
		this.loadProfiles()
	}
	loadProfiles() : void {
		this.profileService.getProfileByProfileId().subscribe(profiles => this.profiles = profiles)
	}
	loadEvents() : void {
		this.eventService.getEventByProfileId().subscribe(events => this.events = events)
	}
}