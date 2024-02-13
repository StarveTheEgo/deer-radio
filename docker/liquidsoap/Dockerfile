FROM savonet/liquidsoap-alpine:v2.2.4

USER root

RUN apk update && \
    apk add --no-cache curl && \
    addgroup -g 0 deerdoor && adduser -u 0 -G deerdoor -S deerdoor

COPY ./docker/liquidsoap/radio/. /var/liquidsoap/radio

RUN chmod -R 775 /var/liquidsoap/radio

WORKDIR /var/liquidsoap/radio

USER deerdoor

CMD ["/var/liquidsoap/radio/scripts/radio.liq"]
