import {Component} from "@angular/core";
import {SessionService} from "./shared/services/session.service";

@Component({
	selector: "angular-example-app",
	template: require("./app.component.html")
})

export class AppComponent {
	constructor(private sessionService : SessionService){
		this.sessionService.setSession().subscribe(foobar=>foobar);
	}
}