def send_notification(message) =
    endpointUrl = control_api_url ^ '/notify-stream-chat/'

    payload = json()
    payload.add('message', message)

    response = http.post(
        headers=[
            ("Content-Type","application/json"),
            ("Accept","application/json")
        ],
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

def get_simple_api_result(endpoint) =
    response = http.get(
        headers=[
            ("Accept","application/json")
        ],
        control_api_url ^ endpoint
    )

    try
        let json.parse ({
            result,
        } : {
            result: string,
        }) = response

        result
    catch err: [error.json] do
        error.raise(error.json, 'Failed parsing {#endpoint} response, status is {#response.status_code}, error: {#err}')
    end
end

def get_deer_image() =
    deer_image_url = get_simple_api_result('/current-deer-image/')

    request.create(deer_image_url)
end

# @note it was not 'force' before
def get_next_track(mode="force") =
    song_url = get_simple_api_result('/next-song/' ^ mode)

    request.create(song_url)
end

def update_now_playing_id(song_id) =
    ignore(get_simple_api_result('/update-now-playing/' ^ string(song_id)))
end

def update_deer_image() =
    log('updating deer image')
    ignore(get_simple_api_result('/update-deer-image/'))
end

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
