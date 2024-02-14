audio_samplerate = 44100 # 48000
video_width = 1920
video_height = 1080
video_framerate = 25

personal_token_path = '/var/radio-storage/apiToken.bin'
control_host = 'deer-radio-nginx'
control_protocol = 'http'
control_api_url = control_protocol ^ '://' ^ control_host ^ '/api/internal'

harbor_server_port = 8080

api_access_token = ref ('')

base_font_size = 28 # 22

author_info = ref ('')
label_info = ref ('')

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

main_font_path = "/var/liquidsoap/radio/font/Roboto_Slab/static/RobotoSlab-Regular.ttf"

streams = ref([])
streamsCounter = ref(0)
