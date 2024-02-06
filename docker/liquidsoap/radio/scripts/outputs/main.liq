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
