import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";

@Injectable()
export class RsvpService {
	constructor(protected http: HttpClient) {
	}
