%include "youtube.liq"

def createOutput(outputName, outputData) =
    _driverName = outputData.driverName

    if _driverName == 'dummy' then
        # Dummy output (streams nowhere)
        streamData = {
            out = output.dummy(combined_stream, fallible = true)
        }
        streams := [...streams(), (outputName, streamData)]
    elsif _driverName == 'google' then
        # YouTube LiveStream
        rtmpUrl = outputData.config.rtmpUrl

        streamData = {
            driverName = _driverName,
            out = output.youtube.live(id="output-#{outputName}", rtmpUrl, combined_stream)
        }
        streams := [...streams(), (outputName, streamData)]
    else
        error.raise(radioError, "Driver #{_driverName} is not supported")
    end
end

def deleteOutput(outputName) =
  if not list.assoc.mem(outputName, streams()) then
    error.raise(radioError, "Stream #{outputName} does not exists!")
  else
    streamData = list.assoc(outputName, streams())
    streamData.out.shutdown()

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

# Gets formatted information about output status
def getOutputState(outputName) =
     if not list.assoc.mem(outputName, streams()) then
        {
            is_active = null,
            is_ready = null,
            is_started = null,
            is_up = null
        }
     else
        streamData = list.assoc(outputName, streams())
        out = streamData.out

        {
            is_active = out.is_active(),
            is_ready = out.is_ready(),
            is_started = out.is_started(),
            is_up = out.is_up()
        }
     end
end
