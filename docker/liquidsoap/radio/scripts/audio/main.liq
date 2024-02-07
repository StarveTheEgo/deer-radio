# MUSIC
deer_audio = request.dynamic(id="deer_audio_stream", timeout=15.0, get_next_track)
deer_audio = blank.skip(deer_audio)
deer_audio = replaygain(deer_audio)
deer_audio = crossfade(deer_audio, smart = true, fade_in = 0.0)
deer_audio = mksafe(deer_audio)
deer_audio.on_track(apply_song)
