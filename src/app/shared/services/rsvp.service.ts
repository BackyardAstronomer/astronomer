import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";
import {Rsvp} from "../interfaces/rsvp";

@Injectable()
export class RsvpService {
	constructor(protected http: HttpClient) {}

	private rsvpUrl = "api/rsvp/";

	postRsvp(rsvp: EventRsvp) : Observable<Status>{
	return(this.http.post<Status>(this.RsvpUrl, Rsvp));
	}

	//call to the rsvp API and create the rsvp requested
	insertRsvp(rsvpId: Rsvp): Observable<Status> {
		return(this.http.post<Status>(this.rsvpUrl, + rsvpId));
	}

	deleteRsvp(rsvpId: string): Observable<Status> {
		return (this.http.delete<Status>(this.rsvpUrl + rsvpId));
	}

	getRsvpByRsvpEventIdRsvpProfileId(rsvpId: string): Observable<Status> {
		return (this.http.delete<Status>(this.rsvpUrl + rsvpId));
	}

	getRsvpByRsvpProfileId(rsvpId: string): Observable<Status> {
		return (this.http.delete<Status>(this.rsvpUrl + rsvpId));
	}

	getRsvpByRsvpEventId(rsvpId: string): Observable<Status> {
		return (this.http.delete<Status>(this.rsvpUrl + rsvpId));
	}

}
