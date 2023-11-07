import { Component, Input, OnInit } from '@angular/core';
import { EChartsOption } from 'echarts';

@Component({
  selector: 'nhe-pie-bar-chart',
  templateUrl: './nhe-pie-bar-chart.component.html',
  styleUrls: ['./nhe-pie-bar-chart.component.scss'],
})
export class NhePieBarChartComponent implements OnInit {
  @Input()
  hasError = false;

  barChartOption: EChartsOption = {
    legend: {
      left: '3%',
      icon: 'circle',
    },
    tooltip: {},
    // dataset: {
    //   source: [
    //     ['product', 'Massa de Entrada'],
    //     ['Cléber Farias dos Santos'],
    //     ['Xion'],
    //     ['Bael'],
    //     ['Arlei'],
    //     ['Leorn'],
    //     ['Esil'],
    //   ],
    // },
    xAxis: {
      type: 'value',
      axisLine: { lineStyle: { color: '#333' } },
      splitLine: { lineStyle: { type: 'dashed', color: '#ddd' } },
    },
    yAxis: {
      type: 'category',
      axisLine: { show: false },
      axisTick: {
        show: false,
      },
      data: ['Cléber ', 'Xion', 'Bael', 'Arlei', 'Leorn', 'Esil'],
    },
    series: [
      {
        type: 'bar',
        itemStyle: { borderRadius: [0, 6, 6, 0] },
        data: [
          { value: 250, itemStyle: { color: '#8BE2BB' } },
          { value: 331, itemStyle: { color: '#FBC191' } },
          { value: 432, itemStyle: { color: '#FD8D8E' } },
          { value: 230, itemStyle: { color: '#61a0a8' } },
          { value: 980, itemStyle: { color: '#d48265' } },
          { value: 532, itemStyle: { color: '#ca8622' } },
        ],
      },
    ],
  };

  chartOption: EChartsOption = {
    title: {
      text: 'Notas com colidência',
    },
    tooltip: {
      trigger: 'item',
      formatter: '{a} <br/>{b}: {c} ({d}%)',
    },
    legend: {
      type: 'scroll',
      orient: 'vertical',
      top: '50%',
      right: 20,
      data: [
        'Cléber Farias dos Santos',
        'Xion ',
        'Bael',
        'Arlei',
        'Esil',
        'Leorn',
      ],
    },
    series: [
      {
        name: 'Notas colididas',
        type: 'pie',
        radius: ['60%', '70%'],
        emphasis: {
          label: {
            show: true,
            fontSize: '15',
            fontWeight: 'bold',
          },
        },
        labelLine: {
          show: true,
        },
        data: [
          {
            value: 250,
            name: 'Cléber Farias dos Santos',
            itemStyle: { color: '#8BE2BB' },
          },
          {
            value: 331,
            name: 'Xion ',
            itemStyle: { color: '#FBC191' },
          },
          {
            value: 432,
            name: 'Bael',
            itemStyle: { color: '#FD8D8E' },
          },
          {
            value: 230,
            name: 'Arlei',
            itemStyle: { color: '#61a0a8' },
          },
          {
            value: 980,
            name: 'Leorn',
            itemStyle: { color: '#d48265' },
          },
          {
            value: 532,
            name: 'Esil',
            itemStyle: { color: '#ca8622' },
          },
        ],
        right: '30%',
      },
    ],
  };

  constructor() {}

  ngOnInit(): void {}
}
