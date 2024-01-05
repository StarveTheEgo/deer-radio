def output.youtube.live(~id="",
  url,
  source) =
  e = %ffmpeg(
    format="flv",
    threads=4,
    %audio(codec="aac", b="320k"),
    %video(
        codec="libx264",
      #  "pixel_format"="vaapi_vld",
      "x264-params"="scenecut=0:open_gop=0:keyint=60:scenecut=0:bframes=0:aud=true",
       # "x264-params"="keyint=60:scenecut=0:bframes=0:aud=true",
        preset="ultrafast",
        b="4500k",
      #  minrate="4500k",
        maxrate="4500k",
        bufsize="8M"
    ),
  #  "hwaccel"="vaapi",
  )

    # output.external(
    #     e,
    #     fallible=true,
    #     "ffmpeg -i - -acodec copy -vcodec copy  -tune zerolatency -threads 0 -rtmp_live live -f flv  \"#{url}/#{key}\"",
    #     source
    # )

  output.url(
    id=id,
    url=url,
    # self_sync = true,
    fallible = true,
    e,
    source)
end
