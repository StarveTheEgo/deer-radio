%include "globals.liq"
%include "init.liq"

%include "interaction/main.liq"

%include "server/main.liq"
%include "metadata/main.liq"

%include "audio/main.liq"
%include "video/main.liq"

%include "outputs/main.liq"


#thread.when({30s}, update_deer_image)

# PERIODIC LINKS
#thread.when({10s}, update_current_shop_links)

# enable_replaygain_metadata()






# Combinate :-D



combined_stream = mux_video(video=deer_video, mksafe(audio))

#combined_stream = mux_video(video=blank(), noise())

output.dummy(combined_stream, fallible = true)






#output_instance = output.youtube.live(
#    livestream_url,
#    # url = restream_host,
#    # key = restream_key,
#    combined_stream
#)

# output_instance.start();

#output.restream.live(
#    audio_encoder = "avenc_aac",
    #audio_encoder = "fdkaacenc",
    #audio_encoder = "mfaacenc",
    #audio_encoder = "voaacenc",
#    video_bitrate = 4500,
#    audio_bitrate= 320000,
#    url = restream_host,
#    key = restream_key,
#    combined_stream
#)


