def send_notification(value) =
    is_sent = process.test("php /var/www/deer-radio/artisan stream-chat:notify $(notification)" % [("notification",
      string.quote(value)
    )])
    if (is_sent) then
        log('Successfully sent notification: ' ^ value)
    else
        log('Could not send notification: ' ^ value)
    end
end

#
def get_deer_image() =
  uri = process.read("php /var/www/deer-radio/artisan deer-image:current")
  request.create(uri)
end

# @note it was not 'force' before
def get_next_track(param="force") =
  uri = process.read("php /var/www/deer-radio/artisan song:next #{param}")
  request.create(uri)
end

def update_deer_image() =
    log('updating deer image')
    ignore(process.read("php /var/www/deer-radio/artisan deer-image:update"))
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
