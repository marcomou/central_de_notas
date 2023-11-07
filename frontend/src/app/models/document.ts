import { DocumentType } from "./documentType";
import { EcoMembership } from "./eco-membership";
import { User } from "./user";

export class Document {
  id: string = '';
  file_name: string = '';
  file_path: string = '';
  url: string = '';
  document_type!: DocumentType;
  annotation?: string;
  metadata?: {[k:string]: any};
  uploader_user!: User;
  eco_membership!: EcoMembership;
  created_at: string = '';
  updated_at: string = '';
  deleted_at?: string;
}