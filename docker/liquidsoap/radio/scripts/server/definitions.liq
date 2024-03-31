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
    initializeOutputs()
    ignore(request)
    response.json({status = "ok"})
end

def srv_outputs_states(request, response) =
    ignore(request)

    result = json()
    streamsCount = list.length(streams())
    log(string(streamsCount))
    for i = 0 to (streamsCount - 1) do
        let (outputName, streamData) = list.nth(streams(), i)
        ignore(streamData)

        result.add(outputName, getOutputState(outputName))
    end

    response.status_code(200)
    response.json({status = "ok", states = result})
end
