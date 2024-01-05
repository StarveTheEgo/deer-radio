settings.init.force_start := true
settings.server.telnet := true


# WARNING: "set" is deprecated and will be removed in future version. Please use `settings.path.to.key.set(value)`


set("log.file", true)
set("log.file.path", "/home/deerftp/liquidsoap-daemon/log/radio-run.log")
#set("init.daemon.pidfile.path","/home/deerftp/liquidsoap-daemon/pid/radio-run.pid")


set("init.catch_exn", false)
set("log.level", 5)
#set("ffmpeg.log.verbosity","debug")
set("log.stdout", true)
set("sandbox", false)
set("sandbox.rw", ['/home/deerftp', '/tmp', '/var/www/deer-radio/storage/app/public/'])

set("frame.audio.samplerate", audio_samplerate)
set("audio.converter.samplerate.converters",["libsamplerate","native"])
set("frame.video.width", video_width)
set("frame.video.height", video_height)
set("frame.video.framerate", video_samplerate)
set("request.grace_time", 120.0) # 600.0

set("video.converter.proportional_scale", false)
#set("decoder.file_decoders",["META", "AAC", "FLAC", "MAD", "WAV", "MIDI", "IMAGE","OGG"])
#set("request.metadata_decoders",["FLAC","OGG","TAGLIB","ID3V2"])
#set("decoder.decoders",["WAV","AIFF","PCM/BASIC","MIDI","IMAGE","RAW AUDIO","FLAC","OGG","MAD"])
#set("decoder.file_extensions.ffmpeg",[])
