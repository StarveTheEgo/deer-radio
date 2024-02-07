def output.youtube.live(~id="", url, source) =
    encodingFormat = %ffmpeg(
        format="flv",
        %audio(codec="aac", b="320k"),
        %video(
          codec="libx264",
          "x264-params"="scenecut=0:open_gop=0:keyint=60:scenecut=0:bframes=0:aud=true",
          preset="ultrafast",
          b="6800k",
          maxrate="6800k",
          bufsize="8M"
        )
    )

    output.url(
        id = id,
        url = url,
        # self_sync = true,
        fallible = true,
        encodingFormat,
        source
    )
end
