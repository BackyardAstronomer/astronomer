import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Status} from "../interfaces/status";
import {CreateAccount} from "../interfaces/create-account";

@Injectable()
export class CreateAccountService {

	constructor(protected http:HttpClient) {}

	private signUpUrl = "api/sign-up/";

	postProfile(profile: CreateAccount) : Observable<Status> {
		return(this.http.post<Status>(this.signUpUrl, profile));
	}
}