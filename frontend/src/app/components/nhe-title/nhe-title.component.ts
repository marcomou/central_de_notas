import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'nhe-title',
  templateUrl: './nhe-title.component.html',
  styleUrls: ['./nhe-title.component.scss']
})
export class NheTitleComponent implements OnInit {
  @Input('routerLinkButtonLeft')
  routerLink?: string;

  constructor() { }

  ngOnInit(): void {
  }

}
