settings.init.force_start := true
settings.server.telnet := true


# WARNING: "set" is deprecated and will be removed in future version. Please use `settings.path.to.key.set(value)`


settings.log.file := true
settings.log.file.path := "/home/deerftp/liquidsoap-daemon/log/radio-run.log"

settings.init.catch_exn := false
settings.log.level := 5
settings.log.stdout := true
settings.sandbox := false
settings.sandbox.rw := ['/var/www/storage/app']

settings.frame.audio.samplerate := audio_samplerate
settings.audio.converter.samplerate.converters := ["libsamplerate", "native"]
settings.frame.video.width := video_width
settings.frame.video.height := video_height
settings.frame.video.framerate := video_samplerate
# settings.request.grace_time := 120.0 # 600.0
# settings.video.converter.proportional_scale := false
