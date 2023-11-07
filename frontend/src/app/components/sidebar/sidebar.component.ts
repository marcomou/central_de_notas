import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

export interface SidebarMenuItem {
  name: string;
  icon: string;
  routerLink: Array<string>;
  menu?: SidebarMenuItem[];
  class?: string;
}

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss'],
})
export class SidebarComponent implements OnInit {
  @Input()
  public menuItems: SidebarMenuItem[] = [];

  @Input()
  public title: string = '';

  @Output()
  public change = new EventEmitter<{ hasMinimized: boolean }>();

  public hasMinimized: boolean = false;

  constructor() {}

  ngOnInit(): void {}

  public changeMinimization = (): void => {
    this.hasMinimized = !this.hasMinimized;

    this.change.emit({ hasMinimized: this.hasMinimized });
  };
}
