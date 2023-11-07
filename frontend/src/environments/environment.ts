import { global_variables } from './global_variables';

// This file can be replaced during build by using the `fileReplacements` array.
// `ng build` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = Object.assign(global_variables, {
  production: false,
  // apiEndpoint: 'http://central-notas-api-staging.eba-5p7ur23k.us-east-1.elasticbeanstalk.com/api',
  // endpoint: 'http://central-notas-api-staging.eba-5p7ur23k.us-east-1.elasticbeanstalk.com',
  // apiEndpoint: 'http://staging.api.centraldepositaria.com.br/api',
  apiEndpoint: 'http://localhost:8500/api',
  endpoint: 'http://localhost:8500',
  clientId: '95a10572-f420-4c21-a7f7-5133b8cf6266',
  clientSecret: 'enHTR9YKMwNCOgfaQo0rRR8RYrvAltd03svixkZ4',
  scope: '',
});

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/plugins/zone-error';  // Included with Angular CLI.
