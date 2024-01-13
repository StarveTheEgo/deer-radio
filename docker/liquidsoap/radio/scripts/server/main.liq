%include "definitions.liq"

#server.register(namespace="restream",
#                description="Redirect a stream.",
#                usage="start <url>",
#                "start",
#                create_stream)
#server.register(namespace="restream",
#                description="Stop a dynamic playlist.",
#                usage="stop <url>",
#                "stop",
#                delete_stream)



harbor.http.register("/healthcheck", port=harbor_server_port, method="GET", srv_healthcheck)
