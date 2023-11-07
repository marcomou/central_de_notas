import { Component, OnInit } from '@angular/core';
import { PageChangedEvent } from 'ngx-bootstrap/pagination';
import { EcoDuty } from 'src/app/models/eco-duty';
import {
  MaterialGoals,
  ResultsByMaterials,
  ReviewsReport,
} from 'src/app/models/reports/reviews';
import { RequestAPI } from 'src/app/models/request';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-annual-report-page',
  templateUrl: './annual-report-page.component.html',
  styleUrls: ['./annual-report-page.component.scss'],
})
export class AnnualReportPageComponent implements OnInit {
  public currentEcoDuty: EcoDuty | undefined;

  public reportReviews = [
    {
      subsection1: 'Em Análise',
      subsection2: 'Em Análise',
      subsection3: 'Pendente',
      subsection4: 'Pendente',
      subsection5: 'Pendente',
      subsection6: 'Pendente',
      proofOfTheGoal: 'Pendente',
    },
  ];

  public resultsAndFulfillmentOfQuantitativeGoalsAPI!:
    | ResultsByMaterials
    | undefined;

  public resultsAndFulfillmentOfQuantitativeGoals: {
    description: string;
    paper: number;
    plastic: number;
    glass: number;
    metal: number;
    type: 't' | 'percentage';
  }[] = [
    {
      description: 'Massa total de produto ou embalagem colocada no mercado',
      paper: 10,
      plastic: 10,
      glass: 10,
      metal: 10,
      type: 't',
    },
    {
      description: 'Meta definida',
      paper: 22,
      plastic: 22,
      glass: 22,
      metal: 22,
      type: 'percentage',
    },
    {
      description: 'Massa a comprovar na meta',
      paper: 2.21,
      plastic: 2.21,
      glass: 2.21,
      metal: 2.21,
      type: 't',
    },
    {
      description: 'Massa total comprovada',
      paper: 0,
      plastic: 0,
      glass: 0,
      metal: 0,
      type: 't',
    },
  ];

  public quantitiesPerOperator = [
    {
      federal_registration: '76446884000174',
      legal_name: 'Razão Social do Operador X',
      validatedInputMass: 0,
      validatedOutputMass: 0,
      validatedFinalMass: 0,
    },
  ];

  public reportRevisionsRequest!: RequestAPI<ReviewsReport[]>;

  public readonly icon_paper = environment.nhe_icons.paper;
  public readonly icon_plastic = environment.nhe_icons.plastic;
  public readonly icon_glass = environment.nhe_icons.glass;
  public readonly icon_metal = environment.nhe_icons.metal;

  constructor(private ecoDutyService: EcoDutyService) {
    this.currentEcoDuty = this.ecoDutyService.currentEcoDuty;
  }

  ngOnInit(): void {
    this.getReviews();
    this.getResultsByMaterials();
  }

  getReviews = (options?: { [k: string]: any }) => {
    this.ecoDutyService.getReviews(this.currentEcoDuty?.id, options).subscribe({
      next: (response) => (this.reportRevisionsRequest = response),
      error: (error) => console.log(error),
    });
  };

  getResultsByMaterials = (options?: { [k: string]: any }) => {
    this.ecoDutyService
      .getResultsByMaterials(this.currentEcoDuty?.id, options)
      .subscribe({
        next: (response) =>
          (this.resultsAndFulfillmentOfQuantitativeGoalsAPI = response.data),
        error: (error) => console.log(error),
      });
  };

  resultsByMaterials = (): ResultsByMaterials => {
    if (this.resultsAndFulfillmentOfQuantitativeGoalsAPI) {
      return new ResultsByMaterials().deserialize(
        this.resultsAndFulfillmentOfQuantitativeGoalsAPI
      );
    }
    return new ResultsByMaterials();
  };

  getTotal(index: number): string {
    let result = this.resultsAndFulfillmentOfQuantitativeGoals[index];

    if (result.type === 'percentage') {
      return '-';
    }

    return (
      result.paper +
      result.plastic +
      result.glass +
      result.metal
    ).toString();
  }

  pageChanged(event: PageChangedEvent) {
    let options: { [k: string]: any } = {};

    if (event?.page) {
      options['page'] = event?.page;
    }

    this.getReviews(options);
  }
}
