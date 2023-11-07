import {
  Component,
  EventEmitter,
  Input,
  OnInit,
  Output,
  ViewEncapsulation,
} from '@angular/core';

export interface HeaderTableItem {
  name: string;
  class?: string;
  sortable?: boolean;
  sorted?: { asc?: boolean; desc?: boolean };
}

export interface ItemTable {
  name: string;
  class?: string;
}

export interface SortedByEvent {
  sortedBy: string;
  asc: boolean | undefined;
}

@Component({
  selector: 'app-generic-table',
  templateUrl: './generic-table.component.html',
  styleUrls: ['./generic-table.component.scss'],
  encapsulation: ViewEncapsulation.None,
})
export class GenericTableComponent implements OnInit {
  @Input()
  headers!: HeaderTableItem[];

  @Input()
  items: ItemTable[] = [];

  @Output()
  sorted: EventEmitter<SortedByEvent> = new EventEmitter();

  constructor() {}

  ngOnInit(): void {}

  sort = (header: HeaderTableItem) => {
    if (header?.sorted) {
      if (header.sorted.asc) {
        header.sorted.desc = !header.sorted.desc;
        header.sorted.asc = !header.sorted.asc;
        this.sorted.emit({ sortedBy: header.name, asc: header.sorted.asc });
      } else if (header.sorted.desc) {
        header.sorted.asc = false;
        header.sorted.desc = !header.sorted.desc;
        this.sorted.emit({ sortedBy: header.name, asc: header.sorted.asc });
      } else {
        header.sorted.asc = !header.sorted.asc;
      }
    } else {
      header.sorted = { asc: true };
      this.sorted.emit({ sortedBy: header.name, asc: header.sorted.asc });
    }

    return header;
  };
}
