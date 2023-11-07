import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { EcoMembership } from 'src/app/models/eco-membership';

@Component({
  selector: 'app-recycler-page',
  templateUrl: './recycler-page.component.html',
  styleUrls: ['./recycler-page.component.scss'],
})
export class RecyclerPageComponent implements OnInit {
  public subpages = {
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
