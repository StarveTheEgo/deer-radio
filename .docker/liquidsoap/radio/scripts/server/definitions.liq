def srv_healthcheck(request, response) =
    response.status_code(200)
    #response.http_version("1.1")
    #response.content_type("application/json")
    ignore(request)
    response.json({status = "ok", uptime = time.up()})
end

#def create_stream(url) =
#  if list.assoc.mem(url, !streams) then
#    "Stream for url #{url} already exists!"
#  else
#    out = output.url(id="restream-#{!count}", fallible=true, url=url, enc, s)
#    count := !count + 1
#    streams := [...!streams, (url, out.shutdown)]
#    "OK!"
#  end
#end

#def delete_stream(url) =
#  if not list.assoc.mem(url, !streams) then
#    "Stream for url #{url} does not exists!"
#  else
#    shutdown = list.assoc(url, !streams)
#    shutdown()
#    streams := list.filter((fun (el) -> fst(el) != url), !streams)
#    "OK!"
#  end
#end
