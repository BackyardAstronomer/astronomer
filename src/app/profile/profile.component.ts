import {Component} from "@angular/core";


@Component({
	template: require("./profile.component.html")
})

export class ProfileComponent implements OnInit{
	users: User[];
	events: Events[];
	status: Status = {status:null, message:null, type:null};

	constructor(private )
}