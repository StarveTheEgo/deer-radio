%include "globals.liq"
%include "init.liq"

%include "interaction/main.liq"

%include "metadata/main.liq"

%include "audio/main.liq"
%include "video/main.liq"

%include "outputs/main.liq"

# Deer Radio LiveStream data

ignore(update_api_token())

thread.when({30s}, update_deer_image)
thread.when({10s}, update_api_token)

# PERIODIC LINKS
thread.when({10s}, update_current_shop_links)

# enable_replaygain_metadata()

# Combinate :-D

combined_stream = source.mux.video(video=deer_video, deer_audio)

%include "server/main.liq"
