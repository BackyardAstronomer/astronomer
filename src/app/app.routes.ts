import {RouterModule, Routes} from "@angular/router";
import {APP_BASE_HREF} from "@angular/common";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {SessionService} from "./shared/services/session.service";
import {CreateAccountService} from "./shared/services/create-account.service";
import {CreateAccountComponent} from "./create-account/createAccount.component";
import {SplashComponent} from "./splash/splash.component";
import {ProfileComponent} from "./profile/profile.component";
import {EventComponent} from "./event/event.component";
import {SignInComponent} from "./sign-in/sign-in.component";
import {SignInService} from "./shared/services/sign-in.service";
import {ProfileService} from "./shared/services/profile.service";
import {EventService} from "./shared/services/event.service";
import {CommentService} from "./shared/services/comment.service";
import {RsvpService} from "./shared/services/rsvp.service";


export const allAppComponents = [SplashComponent, ProfileComponent, SignInComponent, EventComponent, CreateAccountComponent];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "profile", component: ProfileComponent},
	{path: "events", component: EventComponent},
	{path: "sign-in", component: SignInComponent},
	{path: "create-account", component: CreateAccountComponent},
	{path: "detailed-event", component: EventComponent},

];

export const appRoutingProviders: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},
	SessionService, CreateAccountService, SignInService, ProfileService, EventService, CommentService, RsvpService
];

export const routing = RouterModule.forRoot(routes);