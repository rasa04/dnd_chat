package websocket

import (
	"ws/internal/lib/logger"

	"github.com/gorilla/websocket"
)

type Client struct {
	Conn    *websocket.Conn
	Send    chan []byte
	GroupID string
}

func (c *Client) ReadPump(hub *Hub) {
	defer func() {
		hub.RemoveConnection(c)
	}()
	for {
		_, message, err := c.Conn.ReadMessage()
		if err != nil {
			hub.logger.Warn("Read error", logger.MakeErrorContext(err))
			break
		}
		hub.logger.Info("Got message: " + string(message))
		hub.Broadcast(c, message)
	}
}

func (c *Client) WritePump(hub * Hub) {
	defer func() {
		hub.RemoveConnection(c)
	}()
	for message := range c.Send {
		if err := c.Conn.WriteMessage(websocket.TextMessage, message); err != nil {
			hub.logger.Error("Cound not write message to connection")
			break
		}
	}
}
