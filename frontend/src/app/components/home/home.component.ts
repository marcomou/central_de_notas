import { Component, OnInit } from '@angular/core';
import { EChartsOption } from 'echarts';
import { CollidenceDashboardData } from 'src/app/models/dashboard/collidence';
import { EcomembershipsByRoles } from 'src/app/models/dashboard/ecoMemberships';
import {
  ValidatedMassByMaterial,
  ValidatedMassByOperator,
} from 'src/app/models/dashboard/mass';
import {
  ecomembershipsRolesBGColor,
  ecomembershipsRolesIcons,
  ecomembershipsRolesNames,
  NotesByStatus,
  statusListColors,
  statusListName,
} from 'src/app/models/dashboard/notes';
import { EcoDuty } from 'src/app/models/eco-duty';
import { User } from 'src/app/models/user';
import { AuthService } from 'src/app/services/auth.service';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss'],
})
export class HomeComponent implements OnInit {
  public communication = environment.nhe_icons.communication;
  public domain = environment.nhe_icons.domain;
  public pie_chart = environment.nhe_icons.pie_chart;
  public recycling_symbol = environment.nhe_icons.recycling_symbol;
  public iconPath = environment.nhe_icons.path;

  public currentUser: User;

  public EcomembershipsByRoles: any;

  public colidenceChartData!: EChartsOption;
  public colidenceChartError = false;
  public validatedByMaterialBarChartData!: EChartsOption;
  public validatedByMaterialChartError = false;
  public notesChartData!: EChartsOption;
  public notesChartError = false;
  public validatedByOperatorBarChartData!: EChartsOption;
  public validatedByOperatorChartError = false;
  public startDate: any;
  public endDate: any;
  public selectedState: any= null;

  public subpages = {
    data: {
      title: 'Dados',
      enabled: true,
      status: 'done',
    },
    // maps: {
    //   title: 'Mapa',
    //   enabled: false,
    //   status: 'done',
    // },
  };

  public materials = [
    {
      name: 'Papel',
      code: 'paper',
      definedGoal: 0,
      lotTotal: 0,
      currentLot: 0,
      background: '#3E4095',
    },
    {
      name: 'Plástico',
      code: 'plastic',
      definedGoal: 0,
      lotTotal: 0,
      currentLot: 0,
      background: '#FB4143',
    },
    {
      name: 'Vidro',
      code: 'glass',
      definedGoal: 0,
      lotTotal: 0,
      currentLot: 0,
      background: '#1DB672',
    },
    {
      name: 'Metal',
      code: 'metal',
      definedGoal: 0,
      lotTotal: 0,
      currentLot: 0,
      background: '#F89747',
    },
  ];

  ecoDuty?: EcoDuty;
  ecoDuties!: EcoDuty[];
  ecoDutyStatus: { [k: string]: string } = {
    replaced: 'Novo',
  };

  states = [
    { value: '', label: 'Todos' },
    { value: 'AC', label: 'Acre' },
    { value: 'AL', label: 'Alagoas' },
    { value: 'AP', label: 'Amapá' },
    { value: 'AM', label: 'Amazonas' },
    { value: 'BA', label: 'Bahia' },
    { value: 'CE', label: 'Ceará' },
    { value: 'DF', label: 'Distrito Federal' },
    { value: 'ES', label: 'Espírito Santo' },
    { value: 'GO', label: 'Goiás' },
    { value: 'MA', label: 'Maranhão' },
    { value: 'MT', label: 'Mato Grosso' },
    { value: 'MS', label: 'Mato Grosso do Sul' },
    { value: 'MG', label: 'Minas Gerais' },
    { value: 'PA', label: 'Pará' },
    { value: 'PB', label: 'Paraíba' },
    { value: 'PR', label: 'Paraná' },
    { value: 'PE', label: 'Pernambuco' },
    { value: 'PI', label: 'Piauí' },
    { value: 'RJ', label: 'Rio de Janeiro' },
    { value: 'RN', label: 'Rio Grande do Norte' },
    { value: 'RS', label: 'Rio Grande do Sul' },
    { value: 'RO', label: 'Rondônia' },
    { value: 'RR', label: 'Roraima' },
    { value: 'SC', label: 'Santa Catarina' },
    { value: 'SP', label: 'São Paulo' },
    { value: 'SE', label: 'Sergipe' },
    { value: 'TO', label: 'Tocantins' }
  ];

  constructor(
    private ecoDutyService: EcoDutyService,
    private authService: AuthService,
    private organizationService: OrganizationService
  ) {
    this.ecoDutyService.getOrganizationEcoDuties().subscribe((response) => {
      this.ecoDuties = response.data || [];
    });

    this.currentUser = this.authService.currentUser;
  }

  ngOnInit(): void {
    let param :any;
    this.getDashData(param);
    EcoDutyService.currentEcoDutyEvent.subscribe(() => this.getDashData(param));
  }

  getDashData = (params : any) => {  
    let id;
    this.getEcoDuty();
    if (id = this.organizationService.currentOrganization?.id) {
      this.loadCollidencesData(id, params);
      this.loadValidatedByMaterial(id, params);
      this.loadNotesData(id, params);
      this.loadValidatedMassByOperatorData(id);
      this.loadEcoMembershipsByRole(id);
    }
    // this.loadGoalsResume();
  };

  getEcoDutyStatus(index: number) {
    return this.ecoDutyStatus[this.ecoDuties[index].status];
  }

  private getEcoDuty(): void {
    this.ecoDuty = this.ecoDutyService.currentEcoDuty;
  }

  private loadCollidencesData = (id: string, param: any) => {
    let params: any = {};
    const ecoDuty = this.ecoDutyService.currentEcoDuty;

    if(param) {
      if(param[0]){
        params.issued_at_start = param[0];
      }
      if(param[1]){
        params.issued_at_end = param[1];
      }
      if(param[2]){
        params.state = param[2];
      }
    }

    if (ecoDuty) {
      params.ecoDuties = ecoDuty.id;
    }

    this.organizationService.getColidences(id, params).subscribe({
      next: (response) => {
        this.colidenceChartData = this.loadColidenceChartData(
          response.data ?? []
        );
      },
      error: () => (this.colidenceChartError = true),
    });
  };

  private loadValidatedByMaterial = (id: string, param: any) => {
    let params: any = {};
    const ecoDuty = this.ecoDutyService.currentEcoDuty;
    if(param) {
      if(param[0]){
        params.issued_at_start = param[0];
      }
      if(param[1]){
        params.issued_at_end = param[1];
      }
      if(param[2]){
        params.state = param[2];
      }
    }

    if (ecoDuty) {
      params.ecoDuties = ecoDuty.id;
    }

    this.organizationService.getValidatedMassByMaterialType(id, params).subscribe({
      next: (response) => {
        this.validatedByMaterialBarChartData =
          this.loadValidatedByMaterialBarChartData(response.data ?? []);
      },
      error: () => (this.validatedByMaterialChartError = true),
    });
  };

  public fetchDate = () => {
    let selectedArray = []
    let selectedState =  this.selectedState
    let startDate =  this.startDate 
    let endDate =  this.endDate 
    selectedArray = [startDate, endDate, selectedState]
    this.getDashData(selectedArray);
  };

  private loadNotesData = (id: string, param: any) => {
    let params: any = {};
    const ecoDuty = this.ecoDutyService.currentEcoDuty;

    if(param) {
      if(param[0]){
        params.issued_at_start = param[0];
      }
      if(param[1]){
        params.issued_at_end = param[1];
      }
      if(param[2]){
        params.state = param[2];
      }
    }
    if (ecoDuty) {
      params.ecoDuties = ecoDuty.id;
    }

    this.organizationService.getInvoicesByStatus(id, params).subscribe({
      next: (response) => {
        this.notesChartData = this.loadNotesChartData(response.data ?? []);
      },
      error: () => (this.notesChartError = true),
    });
  };

  private loadValidatedMassByOperatorData = (id: string) => {
    let params: any = {};
    const ecoDuty = this.ecoDutyService.currentEcoDuty;

    if (ecoDuty) {
      params.ecoDuties = ecoDuty.id;
    }

    this.organizationService.getValidatedMassByOperators(id, params).subscribe({
      next: (response) => {
        this.validatedByOperatorBarChartData =
          this.loadValidatedByOperatorBarChartData(response.data ?? []);
      },
      error: () => (this.validatedByOperatorChartError = true),
    });
  };

  private loadEcoMembershipsByRole = (id: string) => {
    let params: any = {};
    const ecoDuty = this.ecoDutyService.currentEcoDuty;

    if (ecoDuty) {
      params.ecoDuties = ecoDuty.id;
    }

    this.organizationService.getEcoMembershipsByRole(id, params).subscribe({
      next: (response) => {
        const roles: ("operator"|"recycler")[] = ["operator", "recycler"];
        const data = (response.data ?? []).filter((eco: any) => roles.includes(eco.member_role));

        this.EcomembershipsByRoles = this.loadEcomembershipsByRolesLabels(data);

        if (this.EcomembershipsByRoles.length < 2) {
          roles.forEach(role => {
            if (this.EcomembershipsByRoles.findIndex((eco: any) => eco.code === role) === -1) {
              this.EcomembershipsByRoles.push({
                name: ecomembershipsRolesNames[role],
                value: 0,
                icon: ecomembershipsRolesIcons[role],
                background: ecomembershipsRolesBGColor[role],
              });
            }
          });
        }
      },
    });
  };

  private loadGoalsResume = () => {
    if (this.ecoDuty) {
      this.ecoDutyService.getResultsByMaterials().subscribe({
        next: ({ data }: any) => this.prepareMaterials(data),
        error: (error) => console.log(error),
      });
    }
  };

  private prepareMaterials(data: any): void {
    const {
      defined_goals_percent,
      validated_outgoing_operation_masses,
      liability_declarations,
    } = data || {};

    this.materials.map((material) => {
      const findByCode = (obj: { code: string }) => obj.code === material.code;
      const getMassKg = (arr: any[]) => arr?.find(findByCode)?.mass_kg ?? 0;

      material.definedGoal = 0;
      material.lotTotal = 0;
      material.currentLot = 0;

      if (data && Object.keys(defined_goals_percent).includes(material.code)) {
        material.definedGoal = defined_goals_percent[material.code];
        material.lotTotal = getMassKg(validated_outgoing_operation_masses);
        material.currentLot = getMassKg(liability_declarations);
      }
    });
  }

  getMassToProve(index: number): string {
    const ton = this.parseKgToTon(this.calcMassToProve(index));
    return this.formatTon(ton);
  }

  getCurrentLotTon(index: number): string {
    const ton = this.parseKgToTon(this.materials[index].currentLot);
    return this.formatTon(ton);
  }

  formatTon(ton: number, withTon = false): string {
    const options: Intl.NumberFormatOptions = {
      maximumFractionDigits: 3,
      minimumFractionDigits: 3,
    };

    let value = Intl.NumberFormat('pt-BR', options).format(ton);

    if (withTon) {
      value = `${value} t`;
    }

    return value;
  }

  parseKgToTon(kg: number): number {
    return kg / 1000;
  }

  getCurrentPercentage(index: number): string {
    return (
      (this.materials[index].currentLot / this.calcMassToProve(index)) *
      100
    ).toString();
  }

  calcMassToProve(index: number): number {
    let material = this.materials[index];

    return material.lotTotal * (material.definedGoal / 100);
  }

  /**
   * Exemplo mais completo de retorno de ChartOption do tipo
   * piechart.
   * @returns EChartsOption
   */
  public loadNotesChartData = (notesData: NotesByStatus[]): EChartsOption => {
    const names = Object.keys(notesData).map((key) => {
      if (Object.keys(statusListName).includes(key)) {
        return statusListName[key as keyof typeof statusListName];
      }
      return '';
    });

    const data = Object.keys(notesData).map((key: any) => {
      if (Object.keys(statusListName).includes(key)) {
        return {
          value: notesData[key]?.total,
          name: statusListName[key as keyof typeof statusListName],
          itemStyle: {
            color: statusListColors[key as keyof typeof statusListColors],
          },
        };
      }
      return {};
    });

    return {
      tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c} ({d}%)',
      },
      legend: {
        type: 'scroll',
        orient: 'vertical',
        top: '50%',
        right: 20,
        data: names,
      },
      series: [
        {
          name: 'Notas fiscais',
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
          data: data,
          right: '30%',
        },
      ],
    };
  };

  public loadColidenceChartData = (
    collidences: CollidenceDashboardData[]
  ): EChartsOption => {
    const legendData = collidences?.map((data: CollidenceDashboardData) => {
      return data.organization_name;
    });

    const seriesData = collidences?.map((data: CollidenceDashboardData) => ({
      value: data.total_collidences,
      name: data.organization_name,
    }));

    return {
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
        data: legendData,
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
          data: seriesData,
          right: '30%',
        },
      ],
    };
  };

  public roundTo = function(num: number, places: number) {
    const factor = 1 ** places;
    return Math.round(num * factor) / factor;
  };

  public loadValidatedByMaterialBarChartData = (
    validiatedMass: any []
  ): EChartsOption => {
    let series: any[] = [];

    const data = Object.keys(validiatedMass).map((validatedMassKey) => {
      let value = validiatedMass[validatedMassKey as any];

      if (typeof value === 'string') {
        value = parseFloat(value);
      }

      value = this.parseKgToTon(value);

      // series.push(parseFloat(new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }).format(value)));
      series.push(parseFloat(this.formatTon(value).replace('.', '').replace(',', '.')));

      return validatedMassKey;
    });

    return {
      tooltip: Object.assign({
        trigger: "axis",
        axisPointer: {
          type: 'none',
        },
        formatter: (params: any[]) => {
          const value = this.formatTon(params[0].value, true);
          return `${params[0].marker} ${params[0].axisValueLabel} <strong style="font-size:1.17rem;margin-left:15px;float:right;font-family:sans-serif;">${value}</strong>`;
        },
      }),
      label: {
        show: true,
        position: 'top'
      },
      xAxis: {
        type: 'category',
        data: data
      },
      yAxis: {
        type: 'value',
        axisLabel: {
          formatter: (value: number) => this.formatTon(value, true),
        },
      },
      series: [
        {
          type: 'bar',
          data: series,
          label: {
            formatter: (params) => this.formatTon(parseFloat(params.value.toString()), true),
          },
          itemStyle: {
            color: ({ name }) => {
              const colors: { [material: string]: string } = {
                'Papel': '#8BE2BB',
                'Plástico': '#FD8D8E',
                'Metal': '#61A0A8',
                'Vidro': '#FBC191',
              };

              return colors[name];
            },
            borderRadius: [6, 6, 0, 0],
          },
        }
      ],
    }
  };

  public loadValidatedByOperatorBarChartData = (
    validatedMassByOperator: ValidatedMassByOperator[]
  ): EChartsOption => {
    const data = validatedMassByOperator.map((validatedMass) => {
      const mass = new ValidatedMassByOperator().deserialize(validatedMass);
      return [
        validatedMass.front_name,
        mass.massKgByMaterial('paper'),
        mass.massKgByMaterial('plastic'),
        mass.massKgByMaterial('glass'),
        mass.massKgByMaterial('metal'),
      ];
    });

    return {
      legend: {
        left: '3%',
        icon: 'circle',
      },
      dataset: {
        source: [['product', 'Papel', 'Plástico', 'Vidro', 'Metal'], ...data],
      },
      color: ['#8BE2BB', '#FD8D8E', '#FBC191', '#61A0A8'],
      xAxis: {
        type: 'category',
        axisLine: { show: false },
        axisTick: {
          show: false,
        },
      },
      yAxis: {
        type: 'value',
        axisLine: { lineStyle: { color: '#333' } },
        splitLine: { lineStyle: { type: 'dashed', color: '#ddd' } },
      },
      series: [
        { type: 'bar', itemStyle: { borderRadius: [6, 6, 0, 0] } },
        { type: 'bar', itemStyle: { borderRadius: [6, 6, 0, 0] } },
        { type: 'bar', itemStyle: { borderRadius: [6, 6, 0, 0] } },
        { type: 'bar', itemStyle: { borderRadius: [6, 6, 0, 0] } },
      ],
    };
  };

  private loadEcomembershipsByRolesLabels = (
    EcomembershipsByRoles: EcomembershipsByRoles[]
  ): any[] => {
    return EcomembershipsByRoles.map((ecomembership: any) => {
      return {
        code: ecomembership.member_role,
        name: ecomembershipsRolesNames[
          ecomembership.member_role as keyof typeof ecomembershipsRolesNames
        ],
        value: ecomembership.quantity,
        icon: ecomembershipsRolesIcons[
          ecomembership.member_role as keyof typeof ecomembershipsRolesIcons
        ],
        background:
          ecomembershipsRolesBGColor[
            ecomembership.member_role as keyof typeof ecomembershipsRolesBGColor
          ],
      };
    });
  };

  private getIncomingMass = (validatedMass: ValidatedMassByMaterial[]) => {
    return validatedMass.filter(
      (mass) => mass.operation_mass_type === 'validated_incoming_weight'
    );
  };

  private getOutgoingMass = (validatedMass: ValidatedMassByMaterial[]) => {
    return validatedMass.filter(
      (mass) => mass.operation_mass_type !== 'validated_incoming_weight'
    );
  };

  private massMerge = (
    incomingMass: ValidatedMassByMaterial[],
    outgoingMass: ValidatedMassByMaterial[]
  ) => {
    const merged = incomingMass.map((mass) => {
      const outgoingMassData = outgoingMass.find((om) => mass.code === om.code);

      const outgoingMassKg: number = parseFloat(
        outgoingMassData?.mass_kg ?? '00'
      );

      const incomingMassKg: number = parseFloat(mass?.mass_kg ?? '00');

      return {
        ...mass,
        outgoing_mass_kg: outgoingMassKg,
        incoming_mass_kg: incomingMassKg,
      };
    });

    return merged;
  };
}
