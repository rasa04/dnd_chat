package by_user

import (
	"log"
	"net/http"
	"github.com/gorilla/websocket"
	"ws/entities"
)

var upgrader = websocket.Upgrader {
	// ReadBufferSize:  1024,
	// WriteBufferSize: 1024,
	CheckOrigin: func(r *http.Request) bool {
		log.Println("Origin: " + r.Header.Get("Origin"))

        // Return true to turn off origin checking
        return true
    },
}

func Handle()  {
	hub := entities.Hub {
		Clients:    make(map[*entities.Client]bool),
		Broadcast:  make(chan []byte),
		Register:   make(chan *entities.Client),
		Unregister: make(chan *entities.Client),
	}

	// Run websockets hub
	go hub.Run()

	http.HandleFunc(
		"/ws",
		func(w http.ResponseWriter, r *http.Request) {
			conn, err := upgrader.Upgrade(w, r, nil)

			if err != nil {
				log.Println("WebSocket upgrade failed: ", err)

				return
			}

			client := &entities.Client {
				Conn: conn,
				Send: make(chan []byte, 256),
				Hub: &hub,
			}

			hub.Register <- client
			go client.ReadPump()
			go client.WritePump()
		},
	)

	// Run http handler
	error := http.ListenAndServe(":8081", nil)

	// If http handler shut down
	if error != nil {
		log.Fatal("Server failed to start: ", error)
	}
}
