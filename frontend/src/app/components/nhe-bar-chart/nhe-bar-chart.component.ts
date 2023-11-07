import { Component, Input, OnInit } from '@angular/core';
import { EChartsOption } from 'echarts';

@Component({
  selector: 'nhe-bar-chart',
  templateUrl: './nhe-bar-chart.component.html',
  styleUrls: ['./nhe-bar-chart.component.scss'],
})
export class NheBarChartComponent implements OnInit {
  @Input()
  chartOption!: EChartsOption;

  @Input()
  hasError = false;
  constructor() {}

  ngOnInit(): void {}
}
