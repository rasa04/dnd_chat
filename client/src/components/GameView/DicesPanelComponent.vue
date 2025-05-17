<template>
    <div class="flex-1 bg-chat-background-color text-white p-4 rounded-lg flex flex-col items-center">
        <div class="flex items-center gap-2">
            <!-- Уменьшить количество -->
            <button
              @click="decrementCount"
              :disabled="diceCount <= 1 || isRolling"
              class="px-2 py-1 bg-gray-600 hover:bg-gray-500 disabled:opacity-50 rounded"
            >–</button>

            <!-- Иконка и состояние загрузки -->
            <div class="relative">
              <img
                src="@/assets/img/dice.png"
                alt="Dice"
                class="w-16 h-16 cursor-pointer"
                @click="rollDice"
                :class="{'opacity-50 cursor-wait': isRolling}"
              />
              <div
                v-if="isRolling"
                class="absolute inset-0 flex items-center justify-center text-white text-xl"
              >
                …
              </div>
            </div>

            <!-- Количество и тип -->
            <span class="text-white font-bold text-2xl select-none">
              {{ diceCount }}{{ selectedDice }}
            </span>

            <!-- Увеличить количество -->
            <button
              @click="incrementCount"
              :disabled="isRolling"
              class="px-2 py-1 bg-gray-600 hover:bg-gray-500 disabled:opacity-50 rounded"
            >+</button>
        </div>

        <!-- Результат броска -->
        <div v-if="diceResult !== null" class="mt-2 text-yellow-400 text-3xl font-bold">
        {{ diceResult }}
        </div>

        <!-- Сетка дайсов -->
        <!-- вместо кнопок “d4…d100” внизу -->
        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-4 w-full max-w-xs">
            <button
              v-for="dice in diceTypes"
              :key="dice.sides"
              @click="selectedDice = dice.label"
              :class="[
                'w-full py-2 rounded-md text-sm font-semibold transition',
                selectedDice === dice.label
                  ? 'bg-yellow-500 text-gray-900'
                  : 'bg-gray-700 hover:bg-gray-600'
              ]"
            >
              {{ dice.label }}
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
  name: "DicesPanelComponent",
  data() {
    return {
      game: {},
      diceResult: null,
      diceTypes: [
        { label: 'd4', sides: 4 },
        { label: 'd6', sides: 6 },
        { label: 'd8', sides: 8 },
        { label: 'd10', sides: 10 },
        { label: 'd12', sides: 12 },
        { label: 'd20', sides: 20 },
        { label: 'd100', sides: 100 },
      ],
      selectedDice: 'd20', // по-умолчанию
      diceCount: 1, // сколько штук кидаем
      isRolling: false // флаг, когда идёт запрос
    };
  },
  methods: {
    incrementCount() {
      this.diceCount++;
    },
    decrementCount() {
      if (this.diceCount > 1) this.diceCount--;
    },

    async rollDice() {
      if (this.isRolling) return;
      this.isRolling = true;
      this.diceResult = null;

      // Формируем макрос, например "3d6"
      const macro = `${this.diceCount}${this.selectedDice}`;

      try {
        const payload = {
          body: macro,
          game_id: Number(this.$route.params.game_id),
          type: '1'
        };

        // Отправляем на бэкенд
        await axios.post(
          `${import.meta.env.VITE_API_URL}/api/v1/messages/`,
          payload,
          {
            headers: {
              Authorization: localStorage.getItem('TOKEN')
            },
            withCredentials: true
          }
        );

        // Ответа с результатом обычно придёт по WebSocket
        // но для мгновенной отдачи можно парсить из локального расчёта,
        // либо оставить пустым и дать ChatComponent показать приход по WS.

        // Вместо этого сделаем локальный замок результата:
        // (опционально, если хотите сразу увидеть бросок)
        const sides = Number(this.selectedDice.slice(1));
        let sum = 0;
        for (let i = 0; i < this.diceCount; i++) {
          sum += Math.floor(Math.random() * sides) + 1;
        }
        this.diceResult = sum;

      } catch (err) {
        console.error('Dice roll failed:', err);
        // Можно вывести свою ошибку пользователю
      } finally {
        this.isRolling = false;
      }
    },
  },
};
</script>