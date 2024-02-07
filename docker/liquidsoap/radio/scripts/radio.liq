%include "globals.liq"
%include "init.liq"

%include "interaction/main.liq"

%include "server/main.liq"
%include "metadata/main.liq"

%include "audio/main.liq"
%include "video/main.liq"

%include "outputs/main.liq"

# Deer Radio LiveStream data

settingsJson = requestPreparedStreamSettings()
let json.parse ( settings :
    {
        outputs: [{
            rtmpUrl: string
        }]
    }
) = settingsJson

thread.when({30s}, update_deer_image)
thread.when({15m}, update_api_token)

ignore(update_api_token())

# PERIODIC LINKS
thread.when({10s}, update_current_shop_links)

# enable_replaygain_metadata()

# Combinate :-D



deer_audio_raw = ffmpeg.raw.encode.audio(%ffmpeg(%audio.raw), deer_audio)
deer_video_raw = ffmpeg.raw.encode.video(%ffmpeg(%video.raw), deer_video)

deer_video_raw = applyVideoFilter(deer_video_raw)

deer_video_decoded = ffmpeg.raw.decode.video(deer_video_raw)
deer_audio_decoded = ffmpeg.raw.decode.audio(deer_audio_raw)

combined_stream = source.mux.video(video=deer_video_decoded, deer_audio_decoded)

list.iter(fun(output) -> (
    createOutput(output, combined_stream)
), settings.outputs)
