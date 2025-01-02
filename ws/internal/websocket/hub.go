package websocket

import (
	"log/slog"
	"sync"

	"github.com/gorilla/websocket"
)

type Hub struct {
	groupConns  sync.Map // Map[groupID] -> []*Client
	clients     map[*Client]bool
	broadcast   chan []byte
	register    chan *Client
	unregister  chan *Client
	closeChan   chan *websocket.Conn
	logger      *slog.Logger
	mu          sync.Mutex
}

func NewHub(logger *slog.Logger) *Hub {
	h := &Hub{
		clients:    make(map[*Client]bool),
		broadcast:  make(chan []byte),
		register:   make(chan *Client),
		unregister: make(chan *Client),
		closeChan:  make(chan *websocket.Conn),
		logger:     logger,
	}

	go h.Run()

	return h
}

func (h *Hub) Run() {
	for {
		select {
		case client := <-h.register:
			h.logger.Info("Registering client", slog.String("groupID", client.GroupID))
			h.clients[client] = true

		case client := <-h.unregister:
			if _, ok := h.clients[client]; ok {
				h.logger.Info("Unregistering client", slog.String("groupID", client.GroupID))
				delete(h.clients, client)
				client.Conn.Close()
				close(client.Send)
			}

		case message := <-h.broadcast:
			h.logger.Info("Broadcasting message", slog.Int("clients", len(h.clients)))
			for client := range h.clients {
				select {
				case client.Send <- message:
				default:
					h.logger.Warn("Client send channel full, closing connection")
					close(client.Send)
					delete(h.clients, client)
				}
			}

		case conn := <-h.closeChan:
			h.logger.Info("Closing connection")
			conn.Close()
		}
	}
}

func (h *Hub) AddConnection(client *Client) {
	h.mu.Lock()
	defer h.mu.Unlock()

	clients, _ := h.groupConns.LoadOrStore(client.GroupID, []*Client{})
	h.groupConns.Store(client.GroupID, append(clients.([]*Client), client))
	h.register <- client
}

func (h *Hub) RemoveConnection(client *Client) {
	h.mu.Lock()
	defer h.mu.Unlock()

	value, _ := h.groupConns.Load(client.GroupID)
	clients := value.([]*Client)
	for i, c := range clients {
		if c == client {
			clients = append(clients[:i], clients[i+1:]...)
			break
		}
	}
	h.groupConns.Store(client.GroupID, clients)
	h.unregister <- client
}

func (h *Hub) Broadcast(sender *Client, message []byte) {
	clientsByGroupID, ok := h.groupConns.Load(sender.GroupID)
	if !ok {
		h.logger.Warn("Cannot load connection groups for sender")
		return
	}

	clients := clientsByGroupID.([]*Client)
	h.logger.Debug("Found clients for group")
	for _, client := range clients {
		if client != sender {
			h.logger.Debug("Passing message to send channel")
			client.Send <- message
		}
	}
}
