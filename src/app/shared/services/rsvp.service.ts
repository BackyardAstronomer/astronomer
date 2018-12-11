import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";
import {Rsvp} from "../interfaces/rsvp";

@Injectable()
export class RsvpService {
	constructor(protected http: HttpClient) {}

	private rsvpUrl = "api/rsvp/";

	postRsvp(rsvp: Rsvp) : Observable<Status>{
	return(this.http.post<Status>(this.rsvpUrl, rsvp));
	}


	deleteRsvp(rsvpId: string): Observable<Status> {
		return (this.http.delete<Status>(this.rsvpUrl + rsvpId));
	}

	getRsvpByRsvpEventIdRsvpProfileId(rsvpEventId: string, rsvpProfileId: string): Observable<Rsvp[]> {
		return (this.http.get<Rsvp[]>(this.rsvpUrl + "?rsvpEventId=" +  rsvpEventId + "&rsvpProfileId=" + rsvpProfileId));
	}

	getRsvpByRsvpProfileId(rsvpRsvpProfileId: string): Observable<Event[]> {
		return (this.http.get<Event[]>(this.rsvpUrl, {params: new HttpParams().set("rsvpProfileId", rsvpRsvpProfileId)}));
	}

	getRsvpByRsvpEventId(RsvpRsvpEventId: string): Observable<Event> {
		return (this.http.get<Event>(this.rsvpUrl, {params: new HttpParams().set("RsvpRsvpEventId", RsvpRsvpEventId)}));
	}
}


