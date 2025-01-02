package handlers

import (
	"net/http"
	"ws/internal/lib/logger"
	"ws/internal/websocket"
)

func (h *Handlers) GroupHandler(w http.ResponseWriter, r *http.Request) {
	// Checking connections limit
	h.connMu.Lock()
	if h.ActiveConnections >= h.MaxConnections {
		http.Error(w, "Too many connections", http.StatusTooManyRequests)
		h.connMu.Unlock()
		return
	}
	h.ActiveConnections++
	h.connMu.Unlock()
	defer func() {
		h.connMu.Lock()
		h.ActiveConnections--
		h.connMu.Unlock()
	}()

	// Start handling
	conn, err := h.UpgradeConnection(w, r)
	if err != nil {
		h.Logger.Error("WebSocket upgrade failed: ", logger.MakeErrorContext(err))
		return
	}

	groupID := r.URL.Query().Get("id")
	if groupID == "" {
		h.Logger.Warn("Group ID is missing")
		return
	}

	client := &websocket.Client{
		Conn: conn,
		Send: make(chan []byte, 256),
		GroupID: groupID,
	}
	h.Hub.AddConnection(client)

	go client.ReadPump(h.Hub)
	go client.WritePump(h.Hub)
}
