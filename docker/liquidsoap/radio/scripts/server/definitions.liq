def srv_healthcheck(request, response) =
    response.status_code(200)
    ignore(request)
    response.json({status = "ok", uptime = time.up()})
end

def srv_outputs_stop(request, response) =
    resetOutputs()
    ignore(request)
    response.json({status = "ok"})
end

def srv_outputs_init(request, response) =
    initializeOutputs(combined_stream)
    ignore(request)
    response.json({status = "ok"})
end
