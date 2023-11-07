import { Component, Input, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { forkJoin, Observable } from 'rxjs';
import { Contact } from 'src/app/models/contact';
import { EcoMembership } from 'src/app/models/eco-membership';
import { RequestAPI } from 'src/app/models/request';
import { EcoDutyService } from 'src/app/services/eco-duty.service';
import { EcoMembershipService } from 'src/app/services/eco-membership.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { RuleService } from 'src/app/services/rule.service';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-data-adhering-companiy-subpage',
  templateUrl: './data-adhering-companiy-subpage.component.html',
  styleUrls: ['./data-adhering-companiy-subpage.component.scss'],
})
export class DataAdheringCompaniySubpageComponent implements OnInit {
  @Input()
  ecoMembership?: EcoMembership;
  public adheringCompany: FormGroup;
  public organizationForm: FormGroup;
  public legalTypes?: any[];

  constructor(
    private formBuilder: FormBuilder,
    private ecoMembershipService: EcoMembershipService,
    private userService: UserService,
    private ecoDutyService: EcoDutyService,
    private organizationService: OrganizationService,
    private ruleService: RuleService
  ) {
    this.adheringCompany = this.formBuilder.group({
      id: [],
      member_role: ['intermediate'],
      eco_duty_id: [],
      member_organization_id: [],
      extra: this.formBuilder.group({
        zip_code: [''],
        city: [''],
        street: [''],
        state: [''],
        number: [''],
        complement: [''],
      }),
      representantives: this.formBuilder.array([
        this.contactForm('representante_legal'),
      ]),
      interloctors: this.formBuilder.array([this.contactForm('interlocutor')]),
    });

    this.organizationForm = this.formBuilder.group({
      id: [],
      federal_registration: [''],
      legal_name: [''],
      front_name: [''],
      legal_type_id: [''],
    });

    this.adheringCompany.patchValue({
      eco_duty_id: this.ecoDutyService?.currentEcoDuty?.id,
    });
  }

  ngOnInit(): void {
    if (this.ecoMembership) {
      const ecoMembershipId = this.ecoMembership?.id;

      forkJoin([
        this.ecoMembershipService.getEcoMemberShip(ecoMembershipId),
        this.ecoMembershipService.getContacts(
          ecoMembershipId,
          'role=representante_legal'
        ),
        this.ecoMembershipService.getContacts(
          ecoMembershipId,
          'role=interlocutor'
        ),
      ]).subscribe({
        next: (response) => {
          this.ecoMembership = response[0].data;

          this.organizationForm.patchValue({
            ...this.ecoMembership?.member_organization,
            legal_type_id:
              this.ecoMembership?.member_organization?.legal_type_id,
          });

          if (response[1]?.data && response[1]?.data?.length > 0) {
            this.deleteRepresentative(0);
          }
          if (response[2]?.data && response[2]?.data?.length > 0) {
            this.deleteInterloctor(0);
          }

          response[1]?.data?.map((representative): void => {
            const form = this.contactForm('representante_legal');
            form.setValue({
              ...representative,
              eco_membership_id: this.ecoMembership?.id,
            });
            this.representantives.push(form);
          });
          response[2]?.data?.map((interloctor): void => {
            const form = this.contactForm('interlocutor');
            form.setValue({
              ...interloctor,
              eco_membership_id: this.ecoMembership?.id,
            });
            this.interloctors.push(form);
          });

          this.adheringCompany.patchValue({
            member_organization_id: response[0]?.data?.member_organization?.id,
            ...response[0]?.data,
          });
        },
      });
    }
    this.getLegalType();
  }

  get canUpdateAdheringCompany() {
    return this.ruleService.systemRules.canUpdateAdheringCompany;
  }

  get legalTypeName() {
    const legalType = this.organizationForm.get('legal_type_id')?.value;

    if (legalType) {
      return this.legalTypes?.find(({ id }) => id === legalType.id)?.name || '';
    }
    return '';
  }

  // TODO: Add state list
  get stateName() {
    return '';
  }

  get representantives(): FormArray {
    return this.adheringCompany.controls['representantives'] as FormArray;
  }

  get interloctors(): FormArray {
    return this.adheringCompany.controls['interloctors'] as FormArray;
  }

  getContacts = (): Observable<RequestAPI<Contact[]>> => {
    return this.ecoMembershipService.getContacts(this.ecoMembership?.id);
  };

  getLegalType = (): void => {
    this.userService.getLegalTypes().subscribe({
      next: (response) => (this.legalTypes = response.data),
      error: (error) => console.log(error),
    });
  };

  deleteRepresentative = (index: number): void =>
    this.representantives.removeAt(index);

  newRepresentative() {
    this.representantives.push(this.contactForm('representante_legal'));
  }

  deleteInterloctor = (index: number): void =>
    this.interloctors.removeAt(index);

  newInterloctor() {
    this.interloctors.push(this.contactForm('interlocutor'));
  }

  submit() {
    this.hasEcoMembership() ? this.update() : this.store();
  }

  public store = () => {
    this.organizationService
      .storeOrganization(this.organizationForm.value)
      .subscribe({
        next: (response) => {
          this.organizationForm.patchValue({ ...response.data });

          this.adheringCompany.patchValue({
            member_organization_id: response.data?.id,
          });

          this.ecoMembershipService
            .storeEcoMemberShip(this.adheringCompany.value)
            .subscribe({
              next: (newEcoMembership) => {
                this.adheringCompany.patchValue({
                  ...newEcoMembership?.data,
                });

                const interloctorsRequest: Observable<any>[] = [];
                const representantivesRequest: Observable<any>[] = [];

                this.interloctors.value.map((interloctor: any) => {
                  if (interloctor.name) {
                    interloctor.eco_membership_id = newEcoMembership.data?.id;
                    interloctorsRequest.push(
                      this.ecoMembershipService.storeContacts(interloctor)
                    );
                  }
                  return;
                });

                this.representantives.value.map((representative: any) => {
                  if (representative.name) {
                    representative.eco_membership_id =
                      newEcoMembership.data?.id;
                    representantivesRequest.push(
                      this.ecoMembershipService.storeContacts(representative)
                    );
                  }
                  return;
                });

                forkJoin([
                  ...interloctorsRequest,
                  ...representantivesRequest,
                ]).subscribe({
                  next: (response) => console.log(response),
                  error: (error) => console.log(error),
                });
              },
              error: (membershipStoreError) => {},
            });
        },
        error: (error) => {},
      });
  };

  public update = () => {
    const requests = [
      this.organizationService.updateOrganization(this.organizationForm.value),
      this.ecoMembershipService.updateEcoMembership(this.adheringCompany.value),
    ];

    this.interloctors.value.map((interloctor: any) => {
      interloctor.eco_membership_id = this.adheringCompany.value?.id;
      if (interloctor.id) {
        requests.push(this.ecoMembershipService.updateContacts(interloctor));
      } else if (interloctor.name) {
        requests.push(this.ecoMembershipService.storeContacts(interloctor));
      } else {
        return;
      }
    });
    this.representantives.value.map((interloctor: any) => {
      interloctor.eco_membership_id = this.adheringCompany.value?.id;
      if (interloctor.id) {
        requests.push(this.ecoMembershipService.updateContacts(interloctor));
      } else if (interloctor.name) {
        requests.push(this.ecoMembershipService.storeContacts(interloctor));
      } else {
        return;
      }
    });

    forkJoin([...requests]).subscribe({
      next: (response: any) => console.log(response),
      error: (error: any) => console.log(error),
    });
  };

  public hasEcoMembership(): boolean {
    return !!this.ecoMembershipService.currentEcoMembership;
  }

  ngOnDestroy() {
    this.ecoMembershipService.currentEcoMembership = undefined;
  }

  private contactForm = (contactRole: string): FormGroup => {
    return this.formBuilder.group({
      id: [],
      eco_membership_id: [],
      role: [contactRole, Validators.required],
      name: ['', Validators.required],
      document: ['', Validators.required],
      email: ['', Validators.required],
      phone: ['', Validators.required],
      created_at: [''],
      updated_at: [''],
      deleted_at: [''],
    });
  };
}
