import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { EcoMembership } from 'src/app/models/eco-membership';

@Component({
  selector: 'app-adhering-companiy-page',
  templateUrl: './adhering-companiy-page.component.html',
  styleUrls: ['./adhering-companiy-page.component.scss'],
})
export class AdheringCompaniyPageComponent implements OnInit {
  public front_name?: string;

  subpages = {
    data: {
      title: 'Dados',
      enabled: true,
      status: 'done',
    },
    documents: {
      title: 'Documentos',
      enabled: false,
      status: 'pending',
    },
  };

  public ecoMembership?: EcoMembership;

  constructor(private router: Router) {
    if (this.router.getCurrentNavigation()?.extras.state?.['ecoMembership']) {
      this.ecoMembership =
        this.router.getCurrentNavigation()?.extras.state?.['ecoMembership'];
    }
  }

  ngOnInit(): void {}
}
