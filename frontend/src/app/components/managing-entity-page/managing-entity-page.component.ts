import { Component, OnInit, TemplateRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { Observable } from 'rxjs';
import { CpfValidator } from 'src/app/helpers/validators';
import { Organization } from 'src/app/models/organization';
import { User } from 'src/app/models/user';
import { OrganizationService } from 'src/app/services/organization.service';
import { UserService } from 'src/app/services/user.service';
import { environment } from 'src/environments/environment';
import { AlertComponent } from '../alert/alert.component';

@Component({
  selector: 'app-managing-entity-page',
  templateUrl: './managing-entity-page.component.html',
  styleUrls: ['./managing-entity-page.component.scss'],
})
export class ManagingEntityPageComponent implements OnInit {
  public modalRef?: BsModalRef;

  public organization!: Organization;
  public users$!: Observable<User[]>;

  public form!: FormGroup;

  public deletingUsers: { [k: string]: true } = {};
  public showErrorMessage = false;

  public readonly ok = environment.nhe_icons.ok;

  constructor(
    private organizationService: OrganizationService,
    private userService: UserService,
    private modalService: BsModalService,
    private formBuider: FormBuilder
  ) {}

  ngOnInit(): void {
    const { currentRegulatoryBody, currentManagingEntity } =
      this.organizationService;

    this.organization = (currentRegulatoryBody ||
      currentManagingEntity) as Organization;

    if (this.organization.isRegulatoryBody) {
      this.users$ = this.organizationService.regulatoryBodyUsers;
    } else {
      this.users$ = this.organizationService.managingEntityUsers;
    }

    this.prepareForm();
  }

  get isManagingEntity() {
    return this.organization.isManagingEntity;
  }

  public openModal(template: TemplateRef<any>): void {
    this.modalRef = this.modalService.show(template,
      { class: 'modal-lg', animated: true }
    );
  }

  public closeModal() {
    this.form.reset({ federal_registration: '' });
    this.modalRef?.hide();
  }

  // TODO: function update when new endpoint when ready.
  public createUser(): void {
    this.form.disable();

    let params = {
      ...this.form.value,
      password: 'Password1!',
      password_confirmation: 'Password1!',
    };

    this.userService.create(params).subscribe((user) => {
      this.attachUser(user.id).subscribe((users) => {
        this.updateUserList();

        this.form.enable();

        this.closeModal();
        this.openAlert(user.name);
      });
    });
  }

  // TODO: Add message error
  public deleteUser(id: string): void {
    this.deletingUsers[id] = true;

    this.userService.delete(id, this.organization.id).subscribe(() => {
      delete this.deletingUsers[id];

      this.updateUserList();
    });
  }

  public deleteOrganization() {
    this.modalService.show(AlertComponent, {
      class: 'modal-lg',
      animated: true,
      initialState: {
        status: 'question',
        title: 'Atenção!',
        message: 'Tem certeza que deseja excluir o cadastro desta organização?',
        onPrimary: () => {
          this.organizationService.deleteCurrentOrganization().subscribe(() => {
            window.location.reload();
          });
        }
      }
    });
  }

  private attachUser(id: string) {
    return this.organizationService.attachUser(id);
  }

  private updateUserList(): void {
    this.users$ = this.organizationService.users;
  }

  private prepareForm(): void {
    this.form = this.formBuider.group({
      name: [null, Validators.required],
      federal_registration: ['', [Validators.required, CpfValidator.test]],
      email: [null, [Validators.required, Validators.email]]
    });
  }

  private openAlert(username: string): void {
    this.modalService.show(AlertComponent, {
      class: 'modal-lg',
      animated: true,
      initialState: {
        status: 'success',
        message: `Concedemos acesso ao ${username}.`
      }
    });
  }
}
