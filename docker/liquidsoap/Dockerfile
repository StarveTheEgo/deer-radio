FROM savonet/liquidsoap-alpine:v2.3.0

ARG USER_UID
ARG USER_GID

USER root

RUN addgroup -g "${USER_GID}" deerdoor && adduser -u "${USER_UID}" -G deerdoor -S deerdoor

COPY ./docker/liquidsoap/radio/. /var/liquidsoap/radio

RUN chmod -R 775 /var/liquidsoap/radio

WORKDIR /var/liquidsoap/radio

USER deerdoor

CMD ["/var/liquidsoap/radio/scripts/radio.liq"]
