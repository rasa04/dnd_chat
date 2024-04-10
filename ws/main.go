package main

import (
	"log"
	"net/http"

	"github.com/gorilla/websocket"
)

type Client struct {
	conn *websocket.Conn
	send chan []byte
	hub  *Hub // Reference to the Hub
}

type Hub struct {
	clients    map[*Client]bool // The list of clients
	broadcast  chan []byte // Broadcasting channel to users
	register   chan *Client // Registration channel
	unregister chan *Client
}

func (h *Hub) run() {
	for {
		select {
		case client := <-h.register:
			h.clients[client] = true
		case client := <-h.unregister:
			if _, ok := h.clients[client]; ok {
				delete(h.clients, client)
				close(client.send)
			}
		case message := <-h.broadcast:
			for client := range h.clients {
				select {
				case client.send <- message:
				default:
					close(client.send)
					delete(h.clients, client)
				}
			}
		}
	}
}

func (c *Client) readPump() {
	defer func() {
		c.hub.unregister <- c // Unregistering the client
		c.conn.Close()
	}()
	for {
		_, _, err := c.conn.ReadMessage()
		if err != nil {
			log.Println("Read error:", err)
			break
		}
		// Handle the message received from client
	}
}

func (c *Client) writePump() {
	defer func() {
		c.conn.Close()
	}()
	for message := range c.send {
		if err := c.conn.WriteMessage(websocket.TextMessage, message); err != nil {
			log.Println("Error: ", err)
			break
		}
	}
}

var upgrader = websocket.Upgrader {
	// ReadBufferSize:  1024,
	// WriteBufferSize: 1024,
	CheckOrigin: func(r *http.Request) bool {
		log.Println("Origin: " + r.Header.Get("Origin"))

        // Return true to turn off origin checking
        return true
    },
}

func main() {
	hub := Hub {
		clients:    make(map[*Client]bool),
		broadcast:  make(chan []byte),
		register:   make(chan *Client),
		unregister: make(chan *Client),
	}
	go hub.run()

	http.HandleFunc("/ws", func(w http.ResponseWriter, r *http.Request) {
		conn, err := upgrader.Upgrade(w, r, nil)
		if err != nil {
			log.Println(err)
			return
		}
		client := &Client{
			conn: conn,
			send: make(chan []byte, 256),
		}
		hub.register <- client
		go client.readPump()
		go client.writePump()
	})

	http.ListenAndServe(":8081", nil)
	log.Fatal(http.ListenAndServe(":8081", nil))
}
