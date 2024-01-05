%include "formatters.liq"

def apply_song(m) =
  track_id = m["id"]
  song_author := m["artist"]

  log(m["author_links"])
  author_links := of_json(default=[""], m["author_links"])
  log(m["label_links"])
  label_links := of_json(default=[""], m["label_links"])
  update_current_shop_links()

  song_title := m["title"]
  song_album := m["album"]
  song_album_year := m["album_year"]
  song_label := m["label"]

  #song_length := int_of_float(file.duration(m["filename"]))
  #log("Now playing: #{song_author()} by #{song_title()}")
  log("Now playing $(title) by $(artist)" % [("artist", song_author()), ("title", song_title())])

  ignore(process.read("php /var/www/deer-radio/artisan song:updateNowPlaying #{track_id}"))
  log("actually playing")
  update_deer_image()
  send_notification(get_formatted_song_notification())

  if (string.length(song_label()) > 0) then
    send_notification(get_formatted_label_notification())
  end
end

def apply_deer_image(m) =
      deer_image_link := m["image_link"]
      deer_image_profile_link := m["profile_link"]
      deer_image_author := m["author"]
      deer_image_desc := m["description"]
      log("Got new deer image: $(annotation)" % [("annotation", deer_image_author())])
      #if (string.length(deer_image_link()) > 0) then
      #   send_notification(get_formatted_photo_notification())
      #end
end

