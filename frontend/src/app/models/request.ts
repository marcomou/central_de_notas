export class RequestAPI<Type> {
  data?: Type;
  links?: {
    first: string;
    last: string;
    prev: string;
    next: string;
  };
  meta: Metadata = new Metadata();
}

export class Metadata {
  current_page: number = 0;
  from: number = 0;
  last_page: number = 0;
  links!: {
    active: boolean,
    label: string,
    url?: string
  }[];
  path!: string | number;
  per_page!: number;
  to!: number;
  total!: number;
}

export interface RequestPaginateModel2<Type> {
  data: Type[];
  top: number;
  skip: number;
  total: number;
}
