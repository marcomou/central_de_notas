import { Component, Input, OnInit } from '@angular/core';
import { EChartsOption } from 'echarts';

@Component({
  selector: 'nhe-pie-chart',
  templateUrl: './nhe-pie-chart.component.html',
  styleUrls: ['./nhe-pie-chart.component.scss'],
})
export class NhePieChartComponent implements OnInit {
  @Input()
  chartOption!: EChartsOption;

  @Input()
  hasError = false;

  constructor() {}

  ngOnInit(): void {}
}
