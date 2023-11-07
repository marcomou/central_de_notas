import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map, Observable, of, retry } from 'rxjs';
import { environment } from 'src/environments/environment';
import { DocumentType } from '../models/documentType';
import { RequestAPI } from '../models/request';
import { BaseService } from './base.service';

@Injectable({
  providedIn: 'root',
})
export class DocumentTypeService extends BaseService {
  public static readonly CACHE_DOCUMENT_TYPES = 'document_types';

  constructor(private http: HttpClient) {
    super();
  }

  public getAll(): Observable<DocumentType[]> {
    const path = `${environment.apiEndpoint}/document_types`;
    const documents = this.retrieveFromCache(
      DocumentTypeService.CACHE_DOCUMENT_TYPES
    );

    if (documents) {
      return of(documents);
    }

    return this.http.get<RequestAPI<DocumentType[]>>(path).pipe<DocumentType[]>(
      map((response) => {
        this.cacheIt(DocumentTypeService.CACHE_DOCUMENT_TYPES, response.data);
        return response.data as DocumentType[];
      })
    );
  }
}
