/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        principal: '#66c00b', // Color principal
        secundario: '#72cb10', // Color secundario
        terciario: '#5ab507',  // Color terciario
    },
    },
  },
  plugins: [],
}

