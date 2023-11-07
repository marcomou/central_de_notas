import { Component, Input, OnInit } from '@angular/core';
import { AsyncStatus } from 'src/app/models/async-status';
import { Document } from 'src/app/models/document';
import { DocumentType } from 'src/app/models/documentType';
import { DocumentTypeService } from 'src/app/services/document-type.service';
import { DocumentService } from 'src/app/services/document.service';
import { OrganizationService } from 'src/app/services/organization.service';
import { RuleService } from 'src/app/services/rule.service';

@Component({
  selector: 'app-documents-recycler-subpage',
  templateUrl: './documents-recycler-subpage.component.html',
  styleUrls: ['./documents-recycler-subpage.component.scss'],
})
export class DocumentsRecyclerSubpageComponent implements OnInit {
  private status = {
    documentTypes: AsyncStatus.idle,
    documents: AsyncStatus.idle,
  };

  @Input()
  public ecoMembershipId = '';

  public readonly documentTypesRequired = [
    'social_contract_or_statute',
    'business_license',
    'balance_calibration_certificate',
  ];

  public documentTypes: { [k: string]: DocumentType | null } = {
    organization_federal_registration: null,
    social_contract_or_statute: null,
    business_license: null,
    environmental_license_or_waiver: null,
    balance_calibration_certificate: null,
  };

  public documents: { [k: string]: Document | null } = {
    organization_federal_registration: null,
    social_contract_or_statute: null,
    business_license: null,
    environmental_license_or_waiver: null,
    balance_calibration_certificate: null,
  };

  public options!: {
    canUpload: boolean;
    canDownload: boolean;
    canRedirectToNewGuide: boolean;
  };

  constructor(
    private documentTypeService: DocumentTypeService,
    private documentService: DocumentService,
    private organizationService: OrganizationService,
    private ruleService: RuleService
  ) {}

  ngOnInit(): void {
    this.prepareOptions();
    this.loadDocumentTypes();
    this.loadDocuments();
  }

  get isProgressing() {
    return this.documentTypeIsProgressing || this.documentsIsProgressing;
  }

  private get canUpdateRecycler() {
    return this.ruleService.systemRules.canUpdateRecycler;
  }

  private get documentTypeIsProgressing() {
    return this.status.documentTypes.isProcessing;
  }

  private get documentsIsProgressing() {
    return this.status.documents.isProcessing;
  }

  private loadDocuments(): void {
    if (this.documentsIsProgressing) {
      return;
    }
    this.status.documents = AsyncStatus.loading;

    this.documentService.getAll(this.ecoMembershipId).subscribe({
      next: (documents) => {
        documents
          .filter((document) => this.hasCode(document.document_type.code))
          .forEach((document) => {
            this.documents[document.document_type.code] = document;
          });

        this.status.documents = AsyncStatus.idle;
      },
      error: (error) => {
        this.status.documents = AsyncStatus.error(error.code, error.message);
        alert(error.message);
      },
    });
  }

  private loadDocumentTypes(): void {
    if (this.documentTypeIsProgressing) {
      return;
    }
    this.status.documentTypes = AsyncStatus.loading;

    this.documentTypeService.getAll().subscribe({
      next: (documentTypes) => {
        documentTypes
          .filter((documentType) => this.hasCode(documentType.code))
          .forEach((documentType) => {
            this.documentTypes[documentType.code] = documentType;
          });

        this.status.documentTypes = AsyncStatus.idle;
      },
      error: (error) => {
        this.status.documentTypes = AsyncStatus.error(
          error.code,
          error.message
        );
        alert(error.message);
      },
    });
  }

  private prepareOptions(): void {
    this.options = {
      canUpload: this.canUpdateRecycler,
      canDownload: false, // not working
      canRedirectToNewGuide: true,
    };
  }

  private hasCode(code: string): boolean {
    return (
      this.documentTypesRequired.findIndex((CodeRequired) => {
        return CodeRequired === code;
      }) >= 0
    );
  }
}
