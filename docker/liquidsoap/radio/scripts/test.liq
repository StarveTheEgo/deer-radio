settings.init.force_start := true

def srv_healthcheck(request, response) =
    response.status_code(200)
    #response.http_version("1.1")
    #response.content_type("application/json")
    response.json({status = "ok", uptime = time.up()})
end

harbor.http.register("/healthcheck", port=8080, method="GET", srv_healthcheck)

s = noise()
output.dummy(s)
