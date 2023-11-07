import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { EcoDuty } from 'src/app/models/eco-duty';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { RuleService } from 'src/app/services/rule.service';

@Component({
  selector: 'app-general-data-page',
  templateUrl: './general-data-page.component.html',
  styleUrls: ['./general-data-page.component.scss'],
})
export class GeneralDataPageComponent implements OnInit {
  public form!: FormGroup;
  private ecoDuty: EcoDuty;

  constructor(
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private formBuilder: FormBuilder,
    private ecoDutyService: EcoDutyService,
    private ruleService: RuleService
  ) {
    this.ecoDuty = this.activatedRoute.snapshot.data['ecoDuty'] as EcoDuty;

    EcoDutyService.currentEcoDutyEvent.subscribe(ecoDuty => {
      this.ecoDuty = ecoDuty;
      this.prepareForm();
    });

    this.prepareForm();
  }

  ngOnInit(): void {
    if (!this.ecoDuty) {
      this.router.navigate(['/systems']);
    }
  }

  get canUpdateGeneralData() {
    return this.ruleService.systemRules.canUpdateGeneralData;
  }

  public prepareForm(): void {
    this.form = this.formBuilder.group({
      id: [''],
      metadata: this.formBuilder.group({
        system_name: [''],
        residual_object_system: [''],
        url_page: [''],
        url_name: [''],
        description: [''],
        interloctor: this.formBuilder.group({
          name: [''],
          document: [''],
          registration_document: [''],
          phone: [''],
          email: [''],
        }),
        operational_data: this.formBuilder.group({
          support_screening_centers: [true],
          recycling_credit_system: [true],
          recycling_credit_system_residual_percent: this.formBuilder.group({
            papper: [0],
            plastic: [0],
            glass: [0],
            metal: [0],
          }),
        }),
      }),
    });

    if (this.ecoDuty) {
      this.form.patchValue(this.ecoDuty);
    }
  }

  public submit = () => {
    this.ecoDutyService.updateEcoDuty(this.form.value).subscribe({
      next: (response) => console.log(response),
      error: (error) => console.log(error),
    });
  };
}
