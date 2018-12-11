import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Event} from "../interfaces/event";
import {Comment} from "../interfaces/comment";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";
import {EventUser} from "../interfaces/event-user";


@Injectable()
export class EventService {
	constructor(protected http: HttpClient) {
	}

	//define the API endpoint
	private eventUrl = "api/event/";

	// call to the event API and delete the event in question
	deleteEvent(eventId: string): Observable<Status> {
		return (this.http.delete<Status>(this.eventUrl + eventId));
	}

	// call to the event API and edit the event in question
	updateEvent(event: Comment): Observable<Status> {
		return (this.http.put<Status>(this.eventUrl + event.commentId, event));
	}

	// call to the event API and create the event in question
	createEvent(event: Comment): Observable<Status> {
		return (this.http.post<Status>(this.eventUrl, +event));
	}
	//call to the event api to get all
	getAllEvents(): Observable<EventUser[]> {
		return (this.http.get<EventUser[]>(this.eventUrl))
	}
	// call to the event API and get an array of events based off the familyId
	getEventByEventId(eventId: string): Observable<any[]> {
		return (this.http.get<any[]>(this.eventUrl, {params: new HttpParams().set("eventId", eventId)}));
	}
	// call to the event API and get an array of events based off the userId
	getEventByProfileId(eventProfileId: string): Observable<any[]> {
		return (this.http.get<any[]>(this.eventUrl, {params: new HttpParams().set("eventProfileId", eventProfileId)}));
	}

	// call to the event API and get an array of events based off the event name
	getEventByEventEventTypeId(eventEventTypeId: string): Observable<any[]> {
		return (this.http.get<any[]>(this.eventUrl, {params: new HttpParams().set("eventEventTypeId", eventEventTypeId)}));
	}

	// call to the event API and get an array of events based off the eventContent
	getEventByContent(eventContent: string): Observable<Event[]> {
		return (this.http.get<Event[]>(this.eventUrl, {params: new HttpParams().set("eventContent", eventContent)}));
	}
}