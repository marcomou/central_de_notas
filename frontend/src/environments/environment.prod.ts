import { global_variables } from './global_variables';

export const environment = Object.assign(global_variables, {
  production: true,

  apiEndpoint: 'http://central-custodia-staging.us-east-1.elasticbeanstalk.com/api',
  endpoint: 'http://central-custodia-staging.us-east-1.elasticbeanstalk.com',
  clientId: '957ef5b3-b92a-47e0-931a-b1dcc6bb4f1d',
  clientSecret: 'APyQfmDWZh7kk9oFTU3cjONh3rycGn7vXWVJMBVd',
  scope: ''
});
