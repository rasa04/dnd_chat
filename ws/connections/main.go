package connections

import (
	"ws/connections/by_group"
	"ws/connections/by_user"
)

func HandleByGroup() {
	by_group.Handle()
}

func HandleByUser() {
	by_user.Handle()
}
