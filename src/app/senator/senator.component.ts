// remember to rename/reconfigure for our project as this was made for SenArlo (senator.service,etc)
import {Component} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {PubNubAngular} from "pubnub-angular2";
import {SenatorService} from "../shared/services/senator.service";
import {Message} from "../shared/classes/message";
import {Status} from "../shared/classes/status";

@Component({
	template: require("./senator.component.html")
})

export class SenatorComponent {

	chatForm: FormGroup;
	chatMessages: Message[] = [];
	roomName: string = null;
	roomForm: FormGroup;
	status: Status = null;

	constructor(protected formBuilder: FormBuilder, protected pubnub: PubNubAngular, protected senatorService: SenatorService) {
		this.chatForm = this.formBuilder.group({
			chatMessage: ["", [Validators.maxLength(1024), Validators.required]]
		});
		this.roomForm = this.formBuilder.group({
			roomName: ["", [Validators.maxLength(64), Validators.pattern(/^[a-z-]+$/), Validators.required]]
		});
		this.pubnub.init({
			publishKey: "pub-c-d8eb3d22-aab2-4526-a633-a6da83bb3ef7",
			subscribeKey: "sub-c-73e431b8-1cb2-11e8-b6fb-56b8b46ff3aa"
		});

		let parent = this;
		this.pubnub.addListener({
			message: function(pubnubMessage : any) {
				let message = pubnubMessage.message;
				parent.chatMessages.push(message);
			}
		});
	}

	sendChat(): void {
		let message = new Message(this.chatForm.value.chatMessage, new Date(), "senator-arlo");
		this.pubnub.publish({message: message, channel: this.roomName});
	}

	createRoom(): void {
		let roomName = this.roomForm.value.roomName;
		this.senatorService.createRoom(roomName).subscribe(status => {
			if(status.status === 200) {
				this.roomName = roomName;
				this.pubnub.subscribe({channels: [roomName]});
			}
			this.status = status;
		});
	}
}