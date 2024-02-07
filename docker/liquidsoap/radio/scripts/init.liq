settings.init.force_start := true

settings.init.catch_exn := false
settings.log.level := 5
settings.log.stdout := true
settings.sandbox := false
settings.sandbox.rw := ['/var/radio-storage']

settings.frame.audio.samplerate := audio_samplerate

settings.frame.video.width := video_width
settings.frame.video.height := video_height
settings.frame.video.framerate := video_framerate
# settings.video.converter.proportional_scale := false

radioError = error.register("radioError")
