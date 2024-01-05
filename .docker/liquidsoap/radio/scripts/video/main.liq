
# VIDEO BASE
# deer_video = blank()

# DEER IMAGE
# deer_image = request.dynamic(id="deer_image", timeout=15.0, get_deer_image)
# deer_image = on_track(apply_deer_image, deer_image)
# deer_video = add([deer_video, deer_image])

deer_video = request.dynamic(id="deer_image", timeout=15.0, get_deer_image)
deer_video.on_track(apply_deer_image)

# TOP BACKGROUND
# top_background = blank()
# top_background = video.opacity(0.4, top_background)
# top_background = video.fill(top_background)
# #top_background = video.scale(xscale=1.0, yscale=0.04,x=0,y=0,top_background)
# top_background = video.resize(
#     width = video_width,
#     height = 51, # @todo fix magic number here
#     x = 0,
#     y = 0,
#     top_background
# )
# deer_video = add([deer_video, top_background])

# DEER BOTTOM SCREEN PART
# TOP INFO TEXT
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size, # - 2,
    speed = 45,
    x = 10,
    y = 10,
    "This is Deer Radio - find your favorite music while having unique visual deer experience :) ",
#    "This is Moose Radio - find your favorite music while having unique visual moose experience :)",
    deer_video
)


# BOTTOM BACKGROUND
# bottom_background = blank()
# bottom_background = video.opacity(0.5, bottom_background)
# bottom_background = video.fill(bottom_background)
# bottom_background = video.resize(
#     width = video_width,
#     height = video_height / 2,
#     x = 0,
#     y = 825,
#     bottom_background
# )
# deer_video = add([deer_video, bottom_background])

#
# SONG INFO
#

# SONG NAME (AUTHOR - TITLE)
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 30,
    y = 833,
    get_formatted_track_name_text,
    deer_video
)

# AUTHOR'S SHOP LINK
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 30,
    y = 893,
    get_formatted_track_album_text,
    deer_video
)

# SONG ALBUM
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 30,
    y = 945,
    get_formatted_shop_link_text,
    deer_video
)

# SONG LABEL
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 30,
    y = 990,
    get_formatted_track_label_text,
    deer_video
)

deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 30,
    y = 1028,
    get_formatted_track_label_link,
    deer_video
)

# DEER IMAGE ANNOTATION
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 1095,
    y = 833,
    get_formatted_deer_image_author,
    deer_video
)

# DEER IMAGE DESCRIPTION
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size,
    speed = 0,
    x = 1095,
    y = 878,
    get_formatted_deer_image_description,
    deer_video
)


# DEER IMAGE LINK
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 1095,
    y = 923,
    get_formatted_deer_image_link,
    deer_video
)


# DEER IMAGE PROFILE LINK
deer_video = video.add_text(
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 1095,
    y = 968,
    get_formatted_deer_image_profile_link,
    deer_video
)

#
# INFORMATION TEXT
#
deer_video = video.add_text(
    color = 16777215,
    font = main_font_path,
    size = base_font_size + 4,
    speed = 0,
    x = 1095,
    y = 1028,
    "Deer situations: deerfuldeeringdeer@gmail.com",
    #"For any questions write here: deerfuldeeringdeer@gmail.com",
    deer_video
)
