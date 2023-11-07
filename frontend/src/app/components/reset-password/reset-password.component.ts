import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit, TemplateRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { validPassword } from 'src/app/helpers/validators';
import { AuthService } from 'src/app/services/auth.service';
import { environment } from 'src/environments/environment';
import { AlertComponent } from '../alert/alert.component';

@Component({
  selector: 'app-reset-password',
  templateUrl: './reset-password.component.html',
  styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit {

  token: string | null = null;
  form: FormGroup;
  errorHttp: false | string = false;

  public modalRef?: BsModalRef;

  constructor(
    private activateddRoute: ActivatedRoute,
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private modalService: BsModalService,
    private router: Router,
  ) {
    this.form = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      password: [
        '',
        [Validators.required, Validators.minLength(8), validPassword()],
      ],
      password_confirmation: ['', Validators.required],
    });
  }

  readonly title = environment.title;
  readonly image = environment.reciclagem_1;

  ngOnInit(): void {
    this.activateddRoute.queryParams
      .subscribe(params => {
        this.token = params['token'];
      });
  }

  private openAlert(): void {
    this.modalService.show(AlertComponent, {
      class: 'modal-lg',
      animated: true,
      initialState: {
        status: 'success',
        message: `Sua senha foi resetada com sucesso!`
      }
    });
  }

  resetPassword(): void {
    this.form.disable();
    this.errorHttp = false;

    const data = { ...this.form.value, token: this.token };

    this.authService.resetPassword(data).subscribe({
      next: (response) => {
        if (!response) {
          this.form.enable();
          return;
        }

        this.openAlert();

        this.router.navigate(['/login']);
      },
      error: (error: HttpErrorResponse) => {
        if (error.status >= 400) {
          this.errorHttp = "Erro na requisição, tente novamente mais tarde."
        }

        this.form.enable();
      },
    });
  };

  public isSamePassword = (): boolean | undefined => {
    return (
      this.form.get('password')?.touched &&
      this.form.get('password')?.value ===
      this.form.get('password_confirmation')?.value
    );
  };
}
