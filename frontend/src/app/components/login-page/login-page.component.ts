import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from 'src/app/services/auth.service';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-login-page',
  templateUrl: './login-page.component.html',
  styleUrls: ['./login-page.component.scss']
})
export class LoginPageComponent implements OnInit {

  form: FormGroup;
  errorHttp: false|string = false;

  constructor(
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private ecoDutyService: EcoDutyService
  ) {
    this.form = this.formBuilder.group({
      username: [null, Validators.required],
      password: [null, Validators.required]
    });
  }

  readonly title = environment.title;
  readonly image = environment.reciclagem_1;

  ngOnInit(): void {
  }

  signIn(): void {
    this.form.disable();

    this.authService.signIn({ ...this.form.value }).subscribe({
      next: (response) => {
        if (!response) {
          this.form.enable();
          return;
        }
  
        this.authService.checkMe().subscribe(() => {
          this.ecoDutyService.getOrganizationEcoDuties().subscribe((response) => {
            if (response?.data) {
              this.ecoDutyService.currentEcoDuty = response?.data[0];
            }

            window.location.reload();
          });
        });
      }, 
      error: (error: HttpErrorResponse) => {
        if (error.status >= 400) {
          this.errorHttp = "Erro na requisição, tente novamente mais tarde."
        }

        this.form.enable();
      }
    });
  }
}
