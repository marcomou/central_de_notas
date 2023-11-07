import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map, Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Document } from '../models/document';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root',
})
export class DocumentService {
  constructor(private http: HttpClient, private authService: AuthService) {}

  public getAll(ecoMembershipId: string): Observable<Document[]> {
    const path = `${environment.apiEndpoint}/eco_memberships/${ecoMembershipId}/documents`;

    return this.http
      .get<{ data: Document[] }>(path)
      .pipe(map(({ data }) => data));
  }

  public post(body: {
    document_type_id: string;
    eco_membership_id: string;
    file_path: File;
  }): Observable<Document> {
    const user = this.authService.currentUser;
    const path = `${environment.apiEndpoint}/documents`;
    const bodyRequest = new FormData();
    const options = {
      // headers: { 'Content-Type': 'multipart/form-data' },
    };

    for (const property in body) {
      bodyRequest.append(
        property,
        body[property as 'document_type_id' | 'eco_membership_id' | 'file_path']
      );
    }
    bodyRequest.append('uploader_user_id', user.id);

    return this.http
      .post<{ data: Document }>(path, bodyRequest, options)
      .pipe(map(({ data }) => data));
  }
}
