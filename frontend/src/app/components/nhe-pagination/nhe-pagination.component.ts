import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'nhe-pagination',
  templateUrl: './nhe-pagination.component.html',
  styleUrls: ['./nhe-pagination.component.scss'],
})
export class NhePaginationComponent implements OnInit {
  @Input()
  public perPage!: number;

  @Input()
  public maxSize?: number;

  @Input()
  public totalItems!: number;

  @Input()
  public currentPage: number = 1;

  @Output()
  public pageChanged = new EventEmitter();

  constructor() {}

  ngOnInit(): void {}
}
