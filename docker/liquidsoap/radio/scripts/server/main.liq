%include "definitions.liq"

harbor.http.register("/api/healthcheck", port=harbor_server_port, method="GET", srv_healthcheck)

harbor.http.register("/api/outputs/init", port=harbor_server_port, method="GET", srv_outputs_init)

harbor.http.register("/api/outputs/stop", port=harbor_server_port, method="GET", srv_outputs_stop)
