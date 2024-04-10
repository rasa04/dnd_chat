/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.vue"
  ],
  theme: {
    extend: {
      colors: {
        "main-color": "var(--main-color)",
        "second-color": "var(--second-color)",
      }
    },
  },
  plugins: [],
}

