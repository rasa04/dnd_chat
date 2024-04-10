FROM node:lts-alpine

# install simple http server for serving static content
RUN npm install -g http-server

ENV CLIENT_LOCAL_WORK_DIR=../../client
WORKDIR /var/www/client

# copy both 'package.json' and 'package-lock.json' (if available)
COPY ${CLIENT_LOCAL_WORK_DIR}/package*.json ./

# install project dependencies
RUN npm install

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY ${CLIENT_LOCAL_WORK_DIR} .

# build app for production with minification
RUN npm run build

# Copy assets & components explicitly. Vise-versa them does not copying
COPY ${CLIENT_LOCAL_WORK_DIR}/src/assets/img ./dist/assets/img
COPY ${CLIENT_LOCAL_WORK_DIR}/src/components ./dist/assets/components

EXPOSE 8080
CMD ["http-server", "--port", "8080", "-P", "http://localhost:8080?", "dist"]
