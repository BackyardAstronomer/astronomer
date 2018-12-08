import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs";
import {Status} from "../interfaces/status";
import Profile = module;

@Injectable()
export class ProfileService {
	constructor(protected http: HttpClient) {
	}

	//define the API endpoint
	private profileUrl = "api/profile/";

	//call to the user API and create the user in question
	insertProfile(profileId: Profile): Observable<Status> {
		return(this.http.post<Status>(this.profileUrl, + profileId));
	}
	//call to the user API and delete the user in question
	deleteProfile(profileId: string): Observable<Status> {
		return (this.http.delete<Status>(this.profileUrl + profileId));
	}
	//call to the user API and edit the user in question
	updateProfile(profileId: Profile): Observable<Status> {
		return (this.http.put<Status>(this.profileUrl + Profile.profileId, profileId));
	}
	//call to the user API and get a user object based on its Id
	getProfileByProfileId(profileId: string): Observable<Profile> {
		return(this.http.get<Profile>(this.profileUrl + profileId));
	}
	// call to the user API and get an array of users based off the familyId
	getUserByProfileName(profileId: string): Observable<any[]> {
		return (this.http.get<any[]>(this.profileUrl, {params: new HttpParams().set("profileName", profileId)}));
	}
	//call to the user API and get a user object based on its Email
	getProfileByProfileEmail(profileEmail: string): Observable<Profile> {
		return(this.http.get<Profile>(this.profileUrl + profileEmail));
	}
}
