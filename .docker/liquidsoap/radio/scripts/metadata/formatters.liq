# AUTHOR'S SHOP/PERSONAL LINK
def get_formatted_shop_link_text()
    if author_website() == "" then
       ""
    else
        author_website()
    end
end

# TRACK ALBUM
def get_formatted_track_album_text() =
    if song_album() == "" then
        ""
    else
       "Album: $(album) ($(year))" % [
            ("album", song_album()),
            ("year", song_album_year())
        ]
    end
end

# SONG LABEL
def get_formatted_track_label_text() =
    if song_label() == "" then
        ""
    else
       "Label: $(label)" % [
            ("label", song_label()),
        ]
    end
end

# SONG LABEL LINK
def get_formatted_track_label_link() =
    label_website()
end

# A string with Title - Author data
def get_formatted_track_name_text()
   "$(artist) - $(title)" % [
        ("artist", song_author()),
        ("title", song_title())
    ]
end

# DEER IMAGE ANNOTATION
def get_formatted_deer_image_author()
    "Photo by " ^ deer_image_author()
end

# DEER IMAGE DESCRIPTION
def get_formatted_deer_image_description()
    deer_image_desc()
end

# DEER IMAGE LINK
def get_formatted_deer_image_link()
    deer_image_link()
end

# TEXT FOR DEER IMAGE PROFILE LINK
def get_formatted_deer_image_profile_link()
    deer_image_profile_link()
end

def get_formatted_label_notification() =
   if (list.length(label_links()) > 0) then
        label_info := " ($(links))" % [
            ("links", string.concat(separator=" | ", label_links()))
        ]
   else
        label_info := ""
   end
   "Released on label \"$(label)\"" % [("label", song_label())]
   ^
   label_info()
end

def get_formatted_song_notification() =
   if (list.length(author_links()) > 0) then
        author_info := " | You can find more info about $(artist) at: $(links)" % [
            ("artist", song_author()),
            ("links", string.concat(separator=" | ", author_links()))
        ]
   else
        author_info := ""
   end
   "Song: $(artist) - $(title)" % [("artist", song_author()), ("title", song_title())]
   ^
   author_info()
end

def get_formatted_photo_notification() =
    "Photo: $(photo_link) | Photographer: $(author) ($(profile_link))" % [
        ("photo_link", deer_image_link()),
        ("author", deer_image_author()),
        ("profile_link", deer_image_profile_link())
    ]
end
