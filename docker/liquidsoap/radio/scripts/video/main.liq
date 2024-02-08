# VIDEO BASE
overlay = blank()

def applyVideoFilter(s) =
    def filterFunction(graph) =
        video_track = source.tracks(s).video

        filter_video = ffmpeg.filter.video.input(graph, video_track)

        # TOP BACKGROUND
        filter_video = ffmpeg.filter.drawbox(
            x = "0",
            y = "0",
            width = string(video_width),
            height = "51", # @todo fix magic number here
            color = "black@0.4",
            thickness = "fill",
            replace = true,
            graph,
            filter_video
        )

        # DEER BOTTOM SCREEN PART
        # TOP INFO TEXT
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size, # - 2,
            speed = 45,
            x = 10,
            y = 10,
            graph = graph,
            "This is Deer Radio find your favorite music while having unique visual deer experience :) ",
        #    "This is Moose Radio - find your favorite music while having unique visual moose experience :)",
            filter_video
        )

        # BOTTOM BACKGROUND
        filter_video = ffmpeg.filter.drawbox(
            x = "0",
            y = "825",
            width = string(video_width),
            height = string(video_height / 2),
            color = "black@0.5",
            thickness = "fill",
            replace = true,
            graph,
            filter_video
        )

        #
        # SONG INFO
        #

        # SONG NAME (AUTHOR - TITLE)
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 30,
            y = 833,
            graph = graph,
            get_formatted_track_name_text,
            filter_video
        )

        # AUTHOR'S SHOP LINK
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 30,
            y = 893,
            graph = graph,
            get_formatted_track_album_text,
            filter_video
        )

        # SONG ALBUM
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 30,
            y = 945,
            graph = graph,
            get_formatted_shop_link_text,
            filter_video
        )

        # SONG LABEL
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 30,
            y = 990,
            graph = graph,
            get_formatted_track_label_text,
            filter_video
        )

        # SONG LABEL LINK
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 30,
            y = 1028,
            graph = graph,
            get_formatted_track_label_link,
            filter_video
        )

        # DEER IMAGE ANNOTATION
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 1095,
            y = 833,
            graph = graph,
            get_formatted_deer_image_author,
            filter_video
        )

        # DEER IMAGE DESCRIPTION
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size,
            speed = 0,
            x = 1095,
            y = 878,
            graph = graph,
            get_formatted_deer_image_description,
            filter_video
        )

        # DEER IMAGE LINK
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 1095,
            y = 923,
            graph = graph,
            get_formatted_deer_image_link,
            filter_video
        )

        # DEER IMAGE PROFILE LINK
        filter_video = video.add_text.ffmpeg.raw.filter(
            font = main_font_path,
            color = 0xFFFFFF,
            size = base_font_size + 4,
            speed = 0,
            x = 1095,
            y = 968,
            graph = graph,
            get_formatted_deer_image_profile_link,
            filter_video
        )

        #
        # INFORMATION TEXT
        #
        filter_video = video.add_text.ffmpeg.raw.filter(
            color = 0xFFFFFF,
            font = main_font_path,
            size = base_font_size + 4,
            speed = 0,
            x = 1095,
            y = 1028,
            graph = graph,
            "Deer situations: deerfuldeeringdeer@gmail.com",
            #"For any questions write here: deerfuldeeringdeer@gmail.com",
            filter_video
        )

        source({
          video = ffmpeg.filter.video.output(graph, filter_video)
       })
    end

    ffmpeg.filter.create(filterFunction)
end


# DEER IMAGE
deer_image = request.dynamic(id="deer_image", timeout=15.0, get_deer_image)
deer_image.on_track(apply_deer_image)

overlay = buffer(overlay)

deer_video = add([deer_image, overlay])
