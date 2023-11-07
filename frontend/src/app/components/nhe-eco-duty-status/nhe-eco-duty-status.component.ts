import { Component, Input, OnInit } from '@angular/core';
import { EcoDuty } from 'src/app/models/eco-duty';
import { EcoDutyService } from 'src/app/services/eco-duty.service';

@Component({
  selector: 'nhe-eco-duty-status',
  templateUrl: './nhe-eco-duty-status.component.html',
  styleUrls: ['./nhe-eco-duty-status.component.scss'],
})
export class NheEcoDutyStatusComponent implements OnInit {
  @Input()
  ecoDuty?: EcoDuty;

  statusCodes: { [k: string]: string } = {
    replaced: 'Novo'
  };

  get year() {
    return this.ecoDuty?.eco_ruleset.duty_year;
  }

  get identifier() {
    return this.ecoDuty?.managing_code;
  }

  get updatedAt() {
    return this.ecoDuty?.updated_at;
  }

  get status() {
    if (!this.ecoDuty?.status) {
      return null;
    }

    return this.statusCodes[this.ecoDuty.status];
  }

  get ngClass() {
    return {
      green: this.ecoDuty?.status === 'replaced'
    }
  }

  constructor(private ecoDutyService: EcoDutyService) {
    this.ecoDuty = this.ecoDutyService.currentEcoDuty;

    EcoDutyService.currentEcoDutyEvent.subscribe(ecoDuty => {
      this.ecoDuty = ecoDuty;
    });
  }

  ngOnInit(): void {}
}
