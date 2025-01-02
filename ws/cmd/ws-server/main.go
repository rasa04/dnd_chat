package main

import (
	"log/slog"
	"net/http"
	"os"
	"ws/internal/config"
	"ws/internal/handlers"
	"ws/internal/websocket"

	"github.com/go-chi/chi/v5"
	"github.com/go-chi/chi/v5/middleware"
)

const (
	envDev string = "dev"
	envProd string = "prod"
	maxConnections int = 1000
)

func main() {
	cfg := config.MustLoad()
	logger := setupLogger(cfg.Env)
	logger.Debug("Debug messages are enabled")

	logger.Info("Making new hub")
	hub := websocket.NewHub(logger)

	logger.Info("Starting the app", slog.String("env", cfg.Env))

	handlers := handlers.Handlers{
		MaxConnections: maxConnections,
		Hub:    hub,
		Logger: logger,
	}

	router := chi.NewRouter()
	router.Use(middleware.Logger)
	router.Use(middleware.Recoverer)
	router.Route("/ws", func(r chi.Router) {
		r.Get("/group", handlers.GroupHandler)
		r.Get("/user", handlers.UserHandler)
	})

	logger.Info("Server is running", slog.String("address", cfg.Address))
	err := http.ListenAndServe(cfg.Address, router)
	if err != nil {
		logger.Error("Failed to start server:", err)
	}
}

func setupLogger(env string) *slog.Logger {
	switch env {
	case envDev:
		return slog.New(
			slog.NewTextHandler(
				os.Stdout,
				&slog.HandlerOptions{Level: slog.LevelDebug},
			),
		)
	case envProd:
		return slog.New(
			slog.NewJSONHandler(
				os.Stdout,
				&slog.HandlerOptions{Level: slog.LevelInfo},
			),
		)
	default:
		return slog.New(
			slog.NewTextHandler(
				os.Stdout,
				&slog.HandlerOptions{Level: slog.LevelDebug},
			),
		)
	}
}
