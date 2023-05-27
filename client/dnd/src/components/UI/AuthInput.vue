<template>
  <div :id="name"  :class="{ hidden : hidden}">
    <label :for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your {{ name }}</label>
    <div class="flex">
        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300
          rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600"
        >
          <font-awesome-icon :icon="icon" />
        </span>
      <input type="text" :id="name" class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900
          focus:ring-lime-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5
          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
          dark:focus:border-blue-500" :placeholder="name" v-model="inputValue"
      >
    </div>
    <span class="text-red-500 mb-6" v-if="errorTip">
      {{this.inputValue}} {{ this.inputValue === '' ? 'Not' : 'is not' }} a valid {{ name }}
    </span>
  </div>
</template>

<script>
export default {
  name: "AuthInputData",

  data() {
    return {
      inputValue: '',
      errorTip: false,
      hideAnother: false,
      status: false,
    }
  },

  props: {
    name: String,
    icon: Array,
    hidden: Boolean,
    validate: Function,
  },

  watch: {
    inputValue(n) {
      // 1 HIDE OTHER INPUTS
      this.hideAnother = (n !== '')
      // 2 VALIDATE INPUT
      if (this.name === 'email') {
        this.errorTip = (!/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/.test(this.inputValue) && n !== '')
        this.status = (this.inputValue !== '' && !this.errorTip)
      }else if (this.name === 'username') {
        this.errorTip = (n.length < 4 && n !== '')
        this.status = (this.inputValue !== '' && !this.errorTip)
      }else if (this.name === 'password') {
        this.errorTip = (n.length < 8 && n !== '')
        this.status = (this.inputValue !== '' && !this.errorTip)
      }else if (this.name === 'password confirmation') {
        this.errorTip = (n.length < 8 && n !== '')
        this.status = (this.inputValue !== '' && !this.errorTip)
      }
      // CHANGE STATUS
      this.$emit('changeStatus', this.status)
      this.$emit('updateValue', n)
    },
    hideAnother(n) {
      this.$emit('hideAnother', n)
    },
  }
}
</script>

<style scoped>

</style>