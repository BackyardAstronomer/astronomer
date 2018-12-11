import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignIn} from "../shared/interfaces/sign-in";
import {Status} from "../shared/interfaces/status";
import {SignInService} from "../shared/services/sign-in.service";
import {CookieService} from "ng2-cookies";
import {SessionService} from "../shared/services/session.service";
import {ProfileComponent} from "../profile/profile.component";


@Component({
	template: require("./sign-in.component.html")
})

export class SignInComponent implements OnInit {
	signInForm: FormGroup;
	status: Status = {status:null, message:null, type:null};

	//testSelector = document.querySelector('.splash-background');

	constructor( private sessionService: SessionService, private signInService: SignInService, private formBuilder: FormBuilder){}

	ngOnInit() {
		this.signInForm = this.formBuilder.group({
			profileEmail:["",[Validators.maxLength(32), Validators.required]],
			profilePassword:["",[Validators.maxLength(97), Validators.required]]
		});
	}

	signIn() : void {
		let signIn: SignIn = {profileEmail: this.signInForm.value.profileEmail, profilePassword: this.signInForm.value.profilePassword};

		window.localStorage.removeItem("jwt-token");

		this.signInService.postSignIn(signIn)
			.subscribe(status=> {
				this.status = status;
			if(status.status === 200) {
				this.signInForm.reset();
				console.log("Sign In Success!");
			} else {
				alert("Incorrect Email or Password. Try Again.")
			}
	});
	}
	signOut() : void {
	this.signInService.signOut();
}
}