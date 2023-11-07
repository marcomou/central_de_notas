import { Component, OnInit } from '@angular/core';

import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { RequestAPI } from 'src/app/models/request';
import { LegalType } from 'src/app/models/legal-type';
import { AuthService } from 'src/app/services/auth.service';
import { UserService } from 'src/app/services/user.service';
import { environment } from 'src/environments/environment';
import { CpfValidator, validPassword } from 'src/app/helpers/validators';
import { Router } from '@angular/router';

@Component({
  selector: 'app-data-reverse-system-subpage',
  templateUrl: './data-reverse-system-subpage.component.html',
  styleUrls: ['./data-reverse-system-subpage.component.scss']
})
export class DataReverseSystemSubpageComponent implements OnInit {
  public signupForm: FormGroup;
  public userForm: FormGroup;
  public privacyTerms: boolean = true;
  public legalTypes: any;
  public isShown: boolean = false ;

  constructor(
    private formBuilder: FormBuilder,
    private userService: UserService,
    private authService: AuthService,
    private router: Router
  ) {
    this.signupForm = this.createForm();
    this.userForm = this.createUserForm();
  }

  readonly TITLE = environment.title;

  ngOnInit(): void {
    this.getLegalTypes();
  }

  createForm = (): FormGroup => {
    return this.formBuilder.group({
      federal_registration: ['', Validators.required],
      legal_name: ['', Validators.required],
      front_name: ['', Validators.required],
      legal_type_id: ['', Validators.required],
    });
  };

  createUserForm = (): FormGroup => {
    return this.formBuilder.group({
      name: ['', Validators.required],
      federal_registration: ['', [Validators.required, CpfValidator.test]],
      email: ['', [Validators.required, Validators.email]],
      password: [
        'Passw@rd1',
        [Validators.required, Validators.minLength(8), validPassword()],
      ],
      password_confirmation: ['Passw@rd1', Validators.required],
    });
  };

  getLegalTypes = () => {
    this.userService.getLegalTypes().subscribe({
      next: (response: RequestAPI<LegalType[]>): void => {
        this.legalTypes = response.data;
      },
      error: (error) => console.log(error),
    });
  };

  submit = () => {
    const form = {
      ...this.signupForm.value,
      user: { ...this.userForm.value },
    };

    this.authService.signup(form).subscribe({
      next: (response) => {
        this.router.navigate(['/systems/reverse-systems-list/list']);
      },
      error: (error) => {
        console.log(error);
      },
    });
  };

  public isSamePassword = (): boolean | undefined => {
    return (
      this.userForm.get('password')?.touched &&
      this.userForm.get('password')?.value ===
        this.userForm.get('password_confirmation')?.value
    );
  };

}
