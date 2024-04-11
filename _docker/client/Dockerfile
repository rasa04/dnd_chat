FROM node:lts-alpine as base

RUN apk update && apk add --no-cache nano

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

RUN touch ./src/assets/main.css \
    && npx tailwindcss \
        -i ./src/assets/input.css \
        -o ./src/assets/main.css

EXPOSE 8080

FROM base as production

RUN npm run build

# Copy assets & components explicitly. Vise-versa them does not copying
COPY ${CLIENT_LOCAL_WORK_DIR}/src/assets/img ./dist/assets/img
COPY ${CLIENT_LOCAL_WORK_DIR}/src/components ./dist/assets/components

CMD ["http-server", "--port", "8080", "-P", "http://localhost:8080?", "dist"]

FROM base as development
CMD ["npm", "run", "dev"]
