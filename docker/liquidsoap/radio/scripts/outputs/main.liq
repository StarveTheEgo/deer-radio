%include "youtube.liq"

def createOutput(outputName, outputData) =
    driverName = outputData.driverName

    if driverName == 'dummy' then
        # Dummy output (streams nowhere)
        out = output.dummy(combined_stream, fallible = true)
        streams := [...streams(), (outputName, out.shutdown)]
    elsif driverName == 'google' then
        # YouTube LiveStream
        rtmpUrl = outputData.config.rtmpUrl
        out = output.youtube.live(id="output-#{outputName}", rtmpUrl, combined_stream)
        streams := [...streams(), (outputName, out.shutdown)]
    else
        error.raise(radioError, "Driver #{driverName} is not supported")
    end
end

def deleteOutput(outputName) =
  if not list.assoc.mem(outputName, streams()) then
    error.raise(radioError, "Stream #{outputName} does not exists!")
  else
    shutdown = list.assoc(outputName, streams())
    shutdown()
    streams := list.filter((fun (el) -> fst(el) != outputName), streams())
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

def createOutputFromSettingTuple(outputSettingTuple) =
    let (outputName, outputSettings) = outputSettingTuple

    createOutput(outputName, outputSettings)
end

def initializeOutputs() =
    resetOutputs()

    settingsJson = requestPreparedStreamSettings()
    let json.parse ( settings :
        {
            outputs: [
                (string * {
                    driverName: string,
                    config: {
                        rtmpUrl: string
                    }
                })
            ] as json.object
        }
    ) = settingsJson

    list.iter(createOutputFromSettingTuple, settings.outputs)
end
