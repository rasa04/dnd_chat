package entities

import (
	"log"

	"github.com/gorilla/websocket"
)

/*
* Client struct
* Conn - global websocket connection
* Send - the channel where we send message to it to subscribers
* Hub - needs to unregister the client after logic handled
* 
*/
type Client struct {
	Conn *websocket.Conn
	Send chan []byte
	Hub  *Hub // Reference to the Hub
}

// ReadPump continuously reads messages from the WebSocket connection and processes them.
// It reads messages from the client's WebSocket connection and sends them to the hub for further processing.
// When an error occurs during reading or the client disconnects, it closes the WebSocket connection and unregisters the client from the hub.
func (c *Client) ReadPump() {
	defer func() {
		c.Hub.Unregister <- c
		c.Conn.Close()
	}()
	for {
		_, message, err := c.Conn.ReadMessage()

		if err != nil {
			log.Printf("Read error for client %v: %v", c.Conn.LocalAddr(), err)
			break
		}

		// Handle the message received from client
		log.Printf("Received message from client: %s", message)

		// Process the message if needed...

		// Send a response back to the client
		response := []byte("Hello from server! I got your message: " + string(message))
		c.Send <- response
	}
}

// WritePump continuously writes messages from the client's Send channel to the WebSocket connection.
// It listens for messages on the client's Send channel and writes them to the WebSocket connection.
// If there's an error during writing, it logs the error and closes the WebSocket connection.
func (c *Client) WritePump() {
	defer func() {
		c.Conn.Close()
	}()
	for message := range c.Send {
		if err := c.Conn.WriteMessage(websocket.TextMessage, message); err != nil {
			log.Printf("Send error for client %v: %v", c.Conn.LocalAddr(), err)
			break
		}
	}
}
