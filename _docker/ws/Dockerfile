FROM golang:1.22

ENV WS_LOCAL_WORK_DIR=../../ws
ENV WORK_DIR /var/www/ws
WORKDIR ${WORK_DIR}

COPY ${WS_LOCAL_WORK_DIR} ${WORK_DIR}
RUN go mod download && go mod verify
RUN go build -v -o /usr/local/bin/app

CMD ["app"]
