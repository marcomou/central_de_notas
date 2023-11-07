FROM node:16.14.0-alpine3.14

RUN apk add --no-cache bash

RUN touch /root/.bashrc | echo "PS1='\w\$ '" >> /root/.bashrc

RUN npm config set cache /home/node/app/.npm-cache --global

RUN npm install -g nodemon
RUN npm install -g @angular/cli

RUN mkdir -p /home/node/app

USER node

WORKDIR /home/node/app