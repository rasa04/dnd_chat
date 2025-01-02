package handlers

import (
	"log/slog"
	"net/http"
	"sync"

	websocketService "ws/internal/websocket"

	"github.com/gorilla/websocket"
)

type Handlers struct {
	ActiveConnections int
	MaxConnections    int
	Hub               *websocketService.Hub
	Logger            *slog.Logger
	connMu            sync.Mutex
}

func (h *Handlers) UpgradeConnection(w http.ResponseWriter, r *http.Request) (*websocket.Conn, error) {
	upgrader := websocket.Upgrader{
		CheckOrigin: func(r *http.Request) bool {
			h.Logger.Info("Checking origin", slog.String("origin", r.Header.Get("Origin")))
			return true
		},
	}

	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		h.Logger.Error("WebSocket upgrade failed:", slog.Any("error", err))
		return nil, err
	}

	return conn, nil
}
