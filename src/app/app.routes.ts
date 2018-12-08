import {RouterModule, Routes} from "@angular/router";
import {APP_BASE_HREF} from "@angular/common";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {SessionService} from "./shared/services/session.service";
import {CreateAccountService} from "./shared/services/create-account.service";
import {CreateAccountComponent} from "./create-account/createAccount.component";


export const allAppComponents = [CreateAccountComponent];

export const routes: Routes = [
	{path: "", component: CreateAccountComponent}
];

export const appRoutingProviders: any[] = [
	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},
	SessionService, CreateAccountService
];

export const routing = RouterModule.forRoot(routes);