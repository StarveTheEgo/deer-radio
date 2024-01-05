# MUSIC
audio = request.dynamic(id="audio_stream", timeout=15.0, get_next_track)
audio = blank.skip(audio)
#audio = amplify(1., override="replay_gain",audio)
audio = replaygain(audio)
#audio = on_metadata(apply_song_metadata, audio)
audio.on_track(apply_song)
audio = crossfade(audio, smart = false) # @todo smart = true, fade_in = 0.0
#audio = safe_audio(audio)
audio = mksafe(audio)
#audio = buffer(audio)
