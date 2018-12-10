import {Component, OnInit} from "@angular/core";
import {CommentService} from "../shared/services/comment.service";
import {EventService} from "../shared/services/event.service";
import {ProfileService} from "../shared/services/profile.service";
import {Status} from "../shared/interfaces/status";
import {JwtHelperService} from "@auth0/angular-jwt";
import {Profile} from "../shared/interfaces/profile";
import {RsvpService} from "../shared/services/rsvp.service";
import {Rsvp} from "../shared/interfaces/rsvp";


@Component({
	template: require("./profile.component.html")
})

export class ProfileComponent implements OnInit{
	jwtToken: any = this.jwt.decodeToken(localStorage.getItem("jwt-token"));
	profiles: Profile = {profileId: null, profileBio: null, profileEmail: null, profileImage: null, profileName: null};
	events: Event[];
	rsvps: Rsvp[];
	status: Status = {status:null, message:null, type:null};

	constructor(private commentService: CommentService, private eventService: EventService, private profileService: ProfileService, private rsvpService: RsvpService, private jwt: JwtHelperService) {}

	ngOnInit() {
		this.profileService.getProfileByProfileId(this.jwtToken.auth.profileId).subscribe(profiles => this.profiles = profiles);
		this.loadEvents();
		this.loadRsvps();
	}
	loadEvents() : void {
		this.eventService.getEventByProfileId(this.jwtToken.auth.profileId).subscribe(events => this.events = events)
	}
	loadRsvps() : void {
		this.rsvpService.getRsvpByRsvpEventIdRsvpProfileId(this.jwtToken.auth.profileId).subscribe(rsvps => this.rsvps = rsvps)
	}
}
