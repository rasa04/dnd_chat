package handlers

import (
	"net/http"
	"ws/internal/lib/logger"
	"ws/internal/websocket"
)

func (h *Handlers) UserHandler(w http.ResponseWriter, r *http.Request) {
	conn, err := h.UpgradeConnection(w, r)
	if err != nil {
		h.Logger.Error("WebSocket upgrade failed: ", logger.MakeErrorContext(err))
		return
	}

	ID := r.URL.Query().Get("id")
	if ID == "" {
		h.Logger.Warn("ID is missing")
		return
	}

	client := &websocket.Client{
		Conn: conn,
		Send: make(chan []byte, 256),
		GroupID: ID,
	}

	h.Hub.AddConnection(client)

	go client.ReadPump(h.Hub)
	go client.WritePump(h.Hub)
}
