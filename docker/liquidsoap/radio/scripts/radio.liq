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

combined_stream = source.mux.video(video=deer_video, deer_audio)

list.iter(fun(output) -> (
    createOutput(output, combined_stream)
), settings.outputs)
