deer_video = request.dynamic(id="deer_image", timeout=15.0, get_deer_image)
deer_video.on_track(apply_deer_image)

deer_video = video.add_rectangle(
    color = 0x000000,
    alpha = 0.4,
    x = 0,
    y = 0,
    width = video_width,
    height = base_font_size * 2 - 5,
    deer_video
)

# BOTTOM BACKGROUND
deer_video = video.add_rectangle(
    color = 0x000000,
    alpha = 0.4,
    x = 0,
    y = 825,
    width = video_width,
    height = video_height / 2,
    deer_video
)

# DEER BOTTOM SCREEN PART
# TOP INFO TEXT
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = base_font_size, # -2,
    speed = 45,
    x = 10,
    y = 10,
    "This is Deer Radio find your favorite music while having unique visual deer experience :) ",
    #    "This is Moose Radio - find your favorite music while having unique visual moose experience :)",
    deer_video
)

#
# SONG INFO
#

# SONG NAME (AUTHOR - TITLE)
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 30,
    y = 833,
    get_formatted_track_name_text,
    deer_video
)

# AUTHOR'S SHOP LINK
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 30,
    y = 893,
    get_formatted_track_album_text,
    deer_video
)

# SONG ALBUM
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 30,
    y = 945,
    get_formatted_shop_link_text,
    deer_video
)

# SONG LABEL
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 30,
    y = 990,
    get_formatted_track_label_text,
    deer_video
)

# SONG LABEL LINK
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 30,
    y = 1028,
    get_formatted_track_label_link,
    deer_video
)

# DEER IMAGE ANNOTATION
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 1095,
    y = 833,
    get_formatted_deer_image_author,
    deer_video
)

# DEER IMAGE DESCRIPTION
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 1095,
    y = 878,
    get_formatted_deer_image_description,
    deer_video
)

# DEER IMAGE LINK
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 1095,
    y = 923,
    get_formatted_deer_image_link,
    deer_video
)

# DEER IMAGE PROFILE LINK
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 1095,
    y = 968,
    get_formatted_deer_image_profile_link,
    deer_video
)

#
# INFORMATION TEXT
#
deer_video = video.add_text(
    font = main_font_path,
    color = 0xFFFFFF,
    size = (base_font_size + 4),
    x = 1095,
    y = 1028,
    "Deer situations: deerfuldeeringdeer@gmail.com",
    #"For any questions write here: deerfuldeeringdeer@gmail.com",
    deer_video
)

