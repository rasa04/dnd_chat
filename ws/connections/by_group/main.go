package by_group

import (
	"log"
	"net/http"
	"github.com/gorilla/websocket"
)

// Map to store WebSocket connections by group_id
var groupConnections map[string][]*websocket.Conn

// Define WebSocket upgrade configuration
var upgrader = websocket.Upgrader{
	CheckOrigin: func(r *http.Request) bool {
		log.Println("Origin: " + r.Header.Get("Origin"))

        // Return true to turn off origin checking
        return true
    },
}

func Handle()  {
	// Initialize the map for storing WebSocket connections
	groupConnections = make(map[string][]*websocket.Conn)

	// WebSocket endpoint
	http.HandleFunc("/ws", handleWebSocket)

	// Start the server
	log.Println("Server is running on :8081")
	err := http.ListenAndServe(":8081", nil)
	if err != nil {
		log.Fatal("Server failed to start: ", err)
	}
}

// WebSocket handler function
func handleWebSocket(w http.ResponseWriter, r *http.Request) {
	// Upgrade the HTTP connection to a WebSocket connection
	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		log.Println("WebSocket upgrade failed: ", err)
		return
	}
	defer conn.Close()

	// Read the group_id from the request
	groupID := r.URL.Query().Get("group_id")
	if groupID == "" {
		log.Println("Group ID is missing")
		return
	}

	// Add the connection to the map based on group_id
	groupConnections[groupID] = append(groupConnections[groupID], conn)

	// Handle incoming messages
	for {
		_, message, err := conn.ReadMessage()
		if err != nil {
			// Handle read error or client disconnect
			break
		}

		// Broadcast the message to all connections in the group
		broadcastMessage(message, groupID, conn)
	}
}

// Broadcast message to all connections in the group, excluding the sender
func broadcastMessage(message []byte, groupID string, sender *websocket.Conn) {
	connections := groupConnections[groupID]

	// Create a buffered channel to prevent blocking
	done := make(chan struct{}, len(connections))

	for _, conn := range connections {
		// Exclude the sender's connection
		if conn != sender {
			go func (c *websocket.Conn) {
				// Send message to connection
				err := conn.WriteMessage(websocket.TextMessage, message)
				if err != nil {
					// Handle write error
					log.Println("Failed to write message to connection: ", err)
				}
				// Signal that message has been sent
				done <- struct{}{}
			}(conn)
		}
	}

	// Wait for all messages to be sent
	for i := 0; i < len(connections)-1; i++ {
		<-done
	}
}
