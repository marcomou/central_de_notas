import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'nhe-tab',
  templateUrl: './nhe-tab.component.html',
  styleUrls: ['./nhe-tab.component.scss']
})
export class NheTabComponent implements OnInit {

  @Input()
  subpages: {[k:string]: {title: string, enabled: boolean, status: string}} = {}

  @Output()
  subpagesChange = new EventEmitter();

  constructor() {}

  ngOnInit(): void {}

  changePage(page: string): void {
    for (let property in this.subpages) {
      this.subpages[property].enabled = false;
    }

    this.subpages[page].enabled = true;
    this.subpagesChange.emit(this.subpages);
  }

  pageIsEnabled(page: string): boolean {
    return this.subpages[page].enabled;
  }

  pageIsPending(page: string): boolean {
    return this.subpages[page].status === 'pending';
  }

}
