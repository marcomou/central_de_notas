import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit, TemplateRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { AuthService } from 'src/app/services/auth.service';
import { environment } from 'src/environments/environment';
import { AlertComponent } from '../alert/alert.component';

@Component({
  selector: 'app-recovery-password',
  templateUrl: './recovery-password.component.html',
  styleUrls: ['./recovery-password.component.scss']
})
export class RecoveryPasswordComponent implements OnInit {

  form: FormGroup;
  errorHttp: false|string = false;

  public modalRef?: BsModalRef;

  constructor(
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private modalService: BsModalService
  ) {
    this.form = this.formBuilder.group({
      email: [null, Validators.required],
    });
  }

  readonly title = environment.title;
  readonly image = environment.reciclagem_1;

  ngOnInit(): void {
  }

  private openAlert(username: string): void {
    this.modalService.show(AlertComponent, {
      class: 'modal-lg',
      animated: true,
      initialState: {
        status: 'success',
        message: `Enviamos o link de redefinição de senha por e-mail (${username})`
      }
    });
  }

  sendEmail(): void {
    this.form.disable();
    this.errorHttp = false;

    this.authService.sendEmailPasswordResetLink({ ...this.form.value }).subscribe({
      next: (response) => {
        if (!response) {
          this.form.enable();
          return;
        }

        this.openAlert(this.form.value.email);

        this.form = this.formBuilder.group({
          email: [null, Validators.required],
        });

        this.form.enable();
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
