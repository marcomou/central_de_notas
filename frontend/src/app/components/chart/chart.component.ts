import { Component, Input } from '@angular/core';
import * as am4core from '@amcharts/amcharts4/core';
import * as am4maps from '@amcharts/amcharts4/maps';
import { Common } from '../../helpers/common';

@Component({
  selector: 'app-chart',
  templateUrl: './chart.component.html',
  styleUrls: ['./chart.component.scss'],
})
export class ChartComponent {
  @Input() id?: string;
  private chart!: am4maps.MapChart;
  public compensationByLocation!: Array<any>;
  public showResumeData: any;
  public materials: any = {
    papel: { color: 'text-blue', icon: 'description' },
    plastico: { color: 'text-red', icon: 'liquor' },
    vidro: { color: 'text-green', icon: 'science' },
    metal: { color: 'text-warning', icon: 'recycling' },
  };

  constructor() {
    this._loadCompensationMap();
  }

  ngOnInit(): void {
    this._loadCompensationMap();
  }

  ngAfterViewInit(): void {
    this._loadCompensationMap();
  }
  get materialListKeys() {
    return Object.keys(this.materials);
  }

  getMaterialByKey(materialIdentifier: string) {
    return this.materials[materialIdentifier] || {};
  }

  getMaterialGoal(showResumeData: any, materialKey: any) {
    return showResumeData['material_goal_' + materialKey] || 50;
  }

  getMaterialCleared(showResumeData: any, materialKey: any) {
    return showResumeData['material_cleared_' + materialKey] || 30;
  }

  getMaterialPercentCleared(showResumeData: any, materialKey: any) {
    const materialGoal = this.getMaterialGoal(showResumeData, materialKey);
    const materialCleared = this.getMaterialCleared(
      showResumeData,
      materialKey
    );

    if (materialGoal == 0) {
      return 100;
    }

    return (materialCleared / materialGoal) * 100;
  }

  private _loadCompensationMap() {
    const chartColor = am4core.color('#01ee55');
    const greyColor = am4core.color('#ececec');

    let chart = am4core.create(`chartdiv${this.id}`, am4maps.MapChart);
    this.chart = chart;

    chart.geodataSource.url = '/static/mapchart/brasil.json';

    // Set projection
    chart.projection = new am4maps.projections.Mercator();
    const ufToCode: any = Common.stateToCodeMap;

    chart.geodataSource.events.on('parseended', (ev) => {
      let data: any = [];
      for (var i = 0; i < ev.target.data.features.length; i++) {
        const uf = ev.target.data.features[i].id;
        const ufCode = ufToCode[uf] || null;
        // const compensationLocation = this.compensationByLocation[ufCode];
        const color = greyColor;
        data.push({
          id: uf,
          value: 0,
          data: { name: uf, materials: { metal: { image_url: 'teste' } } },
          fill: color,
        });
      }

      polygonSeries.data = data;
    });

    // Create map polygon series
    let polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
    polygonSeries.useGeodata = true;

    // Configure series tooltip
    let polygonTemplate = polygonSeries.mapPolygons.template;
    polygonTemplate.tooltipHTML =
      '<b>{name}: {value}t</b><br><small>Clique para mais detalhes</small>';
    polygonTemplate.nonScalingStroke = true;
    polygonTemplate.propertyFields.fill = 'fill';

    // Create hover state and set alternative fill color
    let hs = polygonTemplate.states.create('hover');
    hs.properties.fill = chartColor.brighten(5);

    polygonSeries.mapPolygons.template.events.on('hit', (ev: any) => {
      console.log(ev.target.dataItem.dataContext);
      this.showResumeData = ev.target.dataItem.dataContext;
    });

    // });
  }
}
