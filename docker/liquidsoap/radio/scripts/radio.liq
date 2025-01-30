%include "globals.liq"
%include "init.liq"

%include "interaction/main.liq"

%include "metadata/main.liq"

%include "audio/main.liq"
%include "video/main.liq"

# Deer Radio LiveStream data

ignore(update_api_token())

# Requests API to update current deer image to a new one
def update_deer_image() =
    log('updating deer image')
    ignore(get_api_response('/deer-image/update/'))
    deer_image.skip()
end
thread.when({30s}, update_deer_image)
thread.when({10s}, update_api_token)

# PERIODIC LINKS
thread.when({10s}, update_current_shop_links)

enable_replaygain_metadata()

# Combinate :-D

combined_stream = source.mux.video(video=deer_video, deer_audio)

%include "outputs/main.liq"
%include "server/main.liq"
