audio_samplerate = 44100 # 48000
video_width = 1920
video_height = 1080
video_samplerate = 25

#control_host = 'deer-radio-php-fpm'

base_font_size = 28 # 22

author_info = ref ("")
label_info = ref ("")

song_author = ref('Author')
song_title = ref('Title')
song_album = ref('')
song_album_year = ref('')
#song_length = ref(0)
author_website = ref('')
author_links = ref([])
author_link_index = ref(-1)

song_label = ref('')
label_website = ref('')
label_links = ref([])
label_link_index = ref(-1)


deer_image_link = ref('')
deer_image_profile_link = ref('')
deer_image_author = ref('')
deer_image_desc = ref('')



# Deer Radio Settings
#settings_json = process.read("php /var/www/deer-radio/artisan stream:prepare")
## settings_json = process.read("php /var/www/deer-radio/artisan radio-settings:get")

#settings = of_json(default=[("list",[("key","value")])], settings_json)
# Livestream settings
#livestream_settings = list.assoc(default=[], "livestream", settings)
## restream_host = livestream_settings["endpoint"]
## restream_key = livestream_settings["api_key"]
#livestream_url = livestream_settings["url"]

##main_font_path = "/usr/share/fonts/truetype/dejavu/DejaVuSerif.ttf"
main_font_path = "/var/liquidsoap/font/RobotoSlab-Regular.ttf"
