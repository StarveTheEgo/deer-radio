FROM savonet/liquidsoap-alpine:v2.2.4

USER root

RUN apk update && \
    apk add --no-cache curl && \
    addgroup -g 1000 deerdoor && adduser -u 1000 -G deerdoor -S deerdoor

COPY --chmod=755 --chown=deerdoor:deerdoor ./docker/liquidsoap/radio/. /var/liquidsoap/radio

WORKDIR /var/liquidsoap/radio

USER deerdoor

CMD ["/var/liquidsoap/radio/scripts/radio.liq"]
