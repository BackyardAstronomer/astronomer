

@Component({
	template: require("./splash.component.html")
})

export class SplashComponent implements OnInit{
	createCarouselEvent: ;

	status: Status = {status:null, message:null, type:null};

	constructor(private commentService: CommentService, private eventService: EventService, private taskService: TaskService, private userService: UserService, private formBuilder: FormBuilder) {}

	ngOnInit() {
		this.userService.getUserByFamilyId('6fa65d66-a7c0-4650-b716-e4d482e48dc9').subscribe(users => this.users = users);
		this.loadUsers()
	}


}