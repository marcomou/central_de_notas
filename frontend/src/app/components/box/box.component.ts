import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'nhe-box',
  templateUrl: './box.component.html',
  styleUrls: ['./box.component.scss'],
})
export class BoxComponent implements OnInit {
  @Input()
  label: string = '';

  @Input()
  isAnCollapse: boolean = false;

  @Input()
  hasLeftButton: boolean = false;

  @Input()
  leftButtonLabel: string = '';

  @Input()
  style: string | null = null;

  @Input()
  class: string = '';

  @Output()
  leftButtonClicked: EventEmitter<any> = new EventEmitter();

  isCollapsed = false;

  constructor() {}

  ngOnInit(): void {}

  leftButtonClick = (): void => {
    this.isCollapsed = false;
    this.leftButtonClicked.emit();
  };
}
