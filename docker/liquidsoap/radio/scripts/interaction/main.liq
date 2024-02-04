def getApiHeaders() =
    [
        ("Content-Type", "application/json"),
        ("Accept", "application/json"),
        ("Authorization", "Bearer #{api_access_token()}")
    ]
end

# Sends specified message to the stream chat(s)
def send_notification(message) =
    endpointUrl = control_api_url ^ '/stream-chat/send-message'

    payload = json()
    payload.add('message', message)

    response = http.post(
        headers=getApiHeaders(),
        data=json.stringify(payload),
        endpointUrl
    )

    is_sent = response.status_code == 200
    if (is_sent) then
        log('Successfully sent notification: ' ^ message)
    else
        log('Could not send notification: ' ^ message)
    end
end

# Makes API request to specified endpoint
def get_api_response(endpoint) =
    http.get(
        headers=getApiHeaders(),
        control_api_url ^ endpoint
    )
end

# Requests for current deer image
def get_deer_image() =
    response = get_api_response('/deer-image/current')
    try
        let json.parse ({
            annotatedPath,
        } : {
            annotatedPath: string,
        }) = response

        request.create(annotatedPath)
    catch err: [error.json] do
        error.raise(error.json, "Failed at requesting the current deer image, status is #{response.status_code}, error: #{err}")
    end
end

# Requests the next track for the queue
def get_next_track() =
    response = get_api_response('/song-queue/enqueue/auto')
    try
        let json.parse ({
            annotatedPath,
        } : {
            annotatedPath: string,
        }) = response

        request.create(annotatedPath)
    catch err: [error.json] do
        error.raise(error.json, "Failed at requesting the next song, status is #{response.status_code}, error: #{err}")
    end
end

# Actualizes currently playing song id for API
def update_now_playing_id(song_id) =
    ignore(get_api_response('/song-queue/update-current-song' ^ string(song_id)))
end

# Requests API to update current deer image to a new one
def update_deer_image() =
    log('updating deer image')
    ignore(get_api_response('/deer-image/update/'))
end

# Actualizes variables with links to stores
def update_current_shop_links() =
    # AUTHOR LINK
    author_links_length = list.length(author_links())
    if (author_links_length > 1) then
        if author_link_index() >= (author_links_length - 1) then
            author_link_index := 0
        else
            author_link_index := author_link_index() + 1
        end
    else
        author_link_index := 0
    end
    author_website := list.nth(default='', author_links(), author_link_index())

    # LABEL LINK
    label_links_lengths = list.length(label_links())
    if (label_links_lengths > 1) then
        if label_link_index() >= (label_links_lengths - 1) then
            label_link_index := 0
        else
            label_link_index := label_link_index() + 1
        end
    else
        label_link_index := 0
    end
    label_website := list.nth(default='', label_links(), label_link_index())
end

# Updates personal access token from file
def update_api_token() =
    api_access_token := file.contents(personal_token_path)
end
