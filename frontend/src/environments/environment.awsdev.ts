import { global_variables } from './global_variables';

export const environment = Object.assign(global_variables, {
  production: true,

  apiEndpoint: 'https://dev.eureciclo.io/central-notas-api/api',
  endpoint: 'https://dev.eureciclo.io/central-notas-api',
  clientId: '95a10572-f420-4c21-a7f7-5133b8cf6266',
  clientSecret: 'enHTR9YKMwNCOgfaQo0rRR8RYrvAltd03svixkZ4',
  scope: '',
});
