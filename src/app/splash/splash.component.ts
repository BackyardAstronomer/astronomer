import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../shared/interfaces/status";
import {CreateAccountService} from "../shared/services/create-account.service";
import {CreateAccount} from "../shared/interfaces/create-account";

@Component({
	selector: "angular-example-app",
	template: require("./splash.component.html")
})

export class SplashComponent implements OnInit {
	createAccountForm: FormGroup;
	status : Status = {status: null, message: null, type: null}

	constructor(private createAccountService : CreateAccountService, private formBuilder : FormBuilder){}

	ngOnInit() {
this.createAccountForm = this.formBuilder.group({
	profileName : ["", [Validators.maxLength(32), Validators.required]],
	profileEmail : ["", [Validators.maxLength(64), Validators.required]],
	profilePassword : ["", [Validators.maxLength(97), Validators.required]],
	profilePasswordConfirm : ["", [Validators.maxLength(97), Validators.required]]
})
	}
	createProfile() : void {
		let profile : CreateAccount = {profileName: this.createAccountForm.value.profileName, profileEmail: this.createAccountForm.value.profileEmail, profilePassword: this.createAccountForm.value.profilePassword, profilePasswordConfirm: this.createAccountForm.value.profilePasswordConfirm};
		this.createAccountService.postProfile(profile)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					alert(status.message);
				}
			});
	}

}
