// import {Component, OnInit} from "@angular/core";
// import {CommentService} from "../shared/services/comment.service";
// import {EventService} from "../shared/services/event.service";
// import {ProfileService} from "../shared/services/profile.service";
// import {Status} from "../shared/interfaces/status";
// import {JwtHelperService} from "@auth0/angular-jwt";
// import {Profile} from "../shared/interfaces/profile";
// import {RsvpService} from "../shared/services/rsvp.service";
// import {Rsvp} from "../shared/interfaces/rsvp";
//
// @Component({
// 	template:require("./comment.component.html")
// })
//
// export class CommentComponent implements OnInit{
// 	jwtToken: any = this.jwt.decodeToken(localStorage.getItem("jwt-token"));
// 	comment: Comment = {commentId: null, commentEventId: null, commentProfileId: null, commentContent: null, commentDate: null};
// 	profile: Profile[];
// 	events: Event[];
// 	rsvp: Rsvp[];
// 	status: Status = {status: null, message:null, type:null};
//
// //	constructor(private commentService: CommentService, private eventService: EventService, private profileService: ProfileService, private rsvpService: RsvpService, private jwt: JwtHelperService )
// }