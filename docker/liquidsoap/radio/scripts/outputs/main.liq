%include "youtube.liq"

def createOutput(outputData, outputSource) =
    url = outputData.rtmpUrl
    if list.assoc.mem(url, streams()) then
        error.raise(radioError, "Stream for url #{url} already exists!")
    else
        # Registering new output
        out = output.youtube.live(id="output-#{streamsCounter()}", url, outputSource)
        streamsCounter := streamsCounter() + 1
        streams := [...streams(), (url, out.shutdown)]
    end
end

def deleteOutput(url) =
  if not list.assoc.mem(url, streams()) then
    error.raise(radioError, "Stream for url #{url} does not exists!")
  else
    shutdown = list.assoc(url, streams())
    shutdown()
    streams := list.filter((fun (el) -> fst(el) != url), streams())
  end
end

def resetOutputTuple(outputTuple) =
    let (outputKey, outputData) = outputTuple
    ignore(deleteOutput(outputKey))
    ignore(outputData)
end

def resetOutputs() =
    list.iter(resetOutputTuple, streams())
end

def initializeOutputs(outputStream) =
    resetOutputs()

    settingsJson = requestPreparedStreamSettings()
    let json.parse ( settings :
        {
            outputs: [{
                rtmpUrl: string
            }]
        }
    ) = settingsJson

    list.iter(fun(output) -> (
        createOutput(output, outputStream)
    ), settings.outputs)
end
