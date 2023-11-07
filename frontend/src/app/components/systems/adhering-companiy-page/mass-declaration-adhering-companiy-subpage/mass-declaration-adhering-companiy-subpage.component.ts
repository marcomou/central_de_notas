import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { forkJoin, retry } from 'rxjs';
import {
  DefinedGoalsWeightMass,
  LiabilityDeclarations,
  ValidatedOutgoingOperationMasses,
} from 'src/app/models/eco-duty';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { environment } from 'src/environments/environment';
import { MaterialMassDeclarationConfig } from './mass-declaration-adhering-companiy-subpage.interface';

@Component({
  selector: 'app-mass-declaration-adhering-companiy-subpage',
  templateUrl: './mass-declaration-adhering-companiy-subpage.component.html',
  styleUrls: ['./mass-declaration-adhering-companiy-subpage.component.scss'],
})
export class MassDeclarationAdheringCompaniySubpageComponent implements OnInit {
  private materialConfig: {
    [k: string]: MaterialMassDeclarationConfig;
  } = {
    paper: {
      name: 'Papel',
      color: '#3E4095',
      icon: environment.nhe_icons.paper,
    },
    plastic: {
      name: 'Plástico',
      color: '#FB4143',
      icon: environment.nhe_icons.plastic,
    },
    glass: {
      name: 'Vidro',
      color: '#1DB672',
      icon: environment.nhe_icons.glass,
    },
    metal: {
      name: 'Metal',
      color: '#F89747',
      icon: environment.nhe_icons.metal,
    },
  };

  public materialNames: ('paper' | 'plastic' | 'metal' | 'glass')[] = [
    'paper',
    'plastic',
    'metal',
    'glass',
  ];

  public materialMass: {
    [k: string]: {
      name: string;
      code: string;
      icon: string;
      color: string;
      definedGoalsPercent: number;
      definedGoalsWeightMass: DefinedGoalsWeightMass;
      liabilityDeclarations: LiabilityDeclarations;
      validatedOutgoingOperationMasses: ValidatedOutgoingOperationMasses;
    };
  } = {};

  public form: FormGroup;

  constructor(
    private ecoDutyService: EcoDutyService,
    private formBuilder: FormBuilder
  ) {
    this.form = this.formBuilder.group({});
  }

  ngOnInit(): void {
    this.loadResultByMaterials();

    EcoDutyService.currentEcoDutyEvent.subscribe(() => {
      this.loadResultByMaterials();
    });
  }

  private loadResultByMaterials(): void {
    this.ecoDutyService.getResultsByMaterials().subscribe((response) => {
      const data = response.data;

      this.materialNames.forEach((material) => {
        if (!data) {
          return;
        }

        this.materialMass[material] = {
          name: this.materialConfig[material]?.name || '',
          code: material,
          icon: this.materialConfig[material]?.icon || '',
          color: this.materialConfig[material]?.color || '#ccc',
          definedGoalsPercent: data.defined_goals_percent[material],
          definedGoalsWeightMass: this.findMaterial(
            data.defined_goals_weight_mass,
            material
          ),
          liabilityDeclarations: this.findMaterial(
            data.liability_declarations,
            material
          ),
          validatedOutgoingOperationMasses: this.findMaterial(
            data.validated_outgoing_operation_masses,
            material
          ),
        };
      });

      this.prepareForm();
    });
  }

  private prepareForm(): void {
    const groups: any = {};

    this.materialNames.forEach((materialName) => {
      const liabilityDeclarationsTon =
        this.materialMass[materialName].liabilityDeclarations.mass_kg / 1000;
      groups[materialName] = [liabilityDeclarationsTon];
    });

    this.form = this.formBuilder.group(groups);
  }

  public calcDefinedGoalsWeightMassTon(material: any): string {
    const { code } = material;
    const definedGoalsPercent = material.definedGoalsPercent;

    const liabilityDeclarationsMassTon = this.form.get(code)?.value || 0;
    const definedGoalsWeightMass =
      liabilityDeclarationsMassTon * (definedGoalsPercent / 100);

    return this.formatMass(definedGoalsWeightMass);
  }

  public getValidatedOutgoingOperationMassesTon(material: any) {
    return this.formatMass(
      material.validatedOutgoingOperationMasses.mass_kg / 1000
    );
  }

  private formatMass(mass: number): string {
    return mass.toFixed(3).replace('.', ',');
  }

  public sumLiabilityDeclarations(code: string) {
    this.changeLiabilityDeclarationValue(code, true);
  }

  public subLiabilityDeclarations(code: string) {
    this.changeLiabilityDeclarationValue(code, false);
  }

  private changeLiabilityDeclarationValue(code: string, sum: boolean): void {
    const group: any = {};
    let value = this.form.get(code)?.value || 0;
    if (typeof value === 'string') {
      value = Number.parseFloat(value);
    }

    if (sum) {
      group[code] = value + 1;
    } else if (value > 1) {
      group[code] = value - 1;
    } else {
      group[code] = 0;
    }

    this.form.patchValue(group);
  }

  private findMaterial(data: { code: string }[], material: string): any {
    const materialDefault = {
      id: null,
      code: material,
      name: this.materialConfig[material]?.name || '',
      mass_kg: 0,
    };

    return data.find(({ code }) => code === material) || materialDefault;
  }

  public submit(): void {
    forkJoin(
      this.materialNames.map((code) => {
        const massTon = this.form.get(code)?.value || 0;
        const massKg = massTon * 1000;
        const liabilityDeclarationId =
          this.materialMass[code].liabilityDeclarations.id;
        const materialTypeId =
          this.materialMass[code]?.liabilityDeclarations.material_type_id;

        if (liabilityDeclarationId) {
          return this.ecoDutyService.patchLiabilityDeclaration(
            liabilityDeclarationId,
            materialTypeId,
            massKg
          );
        }

        return this.ecoDutyService.postLiabilityDeclaration(
          materialTypeId,
          massKg
        );
      })
    )
      .pipe(retry(3))
      .subscribe(() => {
        this.loadResultByMaterials();
      });
  }
}
