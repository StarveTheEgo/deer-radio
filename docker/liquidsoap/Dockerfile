FROM savonet/liquidsoap-alpine:v2.2.4

ARG USER_UID
ARG USER_GID

USER root

RUN apk update && \
    apk add --no-cache curl && \
    addgroup -g "${USER_GID}" deerdoor && adduser -u "${USER_UID}" -G deerdoor -S deerdoor

COPY ./docker/liquidsoap/radio/. /var/liquidsoap/radio

RUN chmod -R 775 /var/liquidsoap/radio

WORKDIR /var/liquidsoap/radio

USER deerdoor

CMD ["/var/liquidsoap/radio/scripts/radio.liq"]
