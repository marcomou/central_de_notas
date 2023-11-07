import { Component, EventEmitter, Input, OnChanges, Output } from '@angular/core';

@Component({
  selector: 'nhe-select',
  templateUrl: './nhe-select.component.html',
  styleUrls: ['./nhe-select.component.scss']
})
export class NheSelectComponent implements OnChanges {

  @Input()
  label: string = '';

  @Input()
  content: {name: string|number, value: any}[] = [];

  @Input()
  prefix: string = '';

  @Input()
  iconSrc: string|false = false;

  @Input()
  maxWidth: string = '230px';

  @Input()
  value: any = false;

  @Output()
  valueChange = new EventEmitter;

  constructor() { }

  ngOnChanges(): void {
    if (this.value) {
      this.content.forEach(({ name, value }) => value === this.value && (this.label = name.toString()));
    }
  }

  setValue(obj: {name: string|number, value: any}): void {
    this.valueChange.emit(obj.value);
    this.label = obj.name.toString();
  }
}
