/** @type {import('tailwindcss').Config} config */
const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    extend: {
      colors: {}, // Extend Tailwind's default colors
      outlineWidth: {
        'none': 'none',
      }
    },
    container: {
      center: true,
    },
  },
  plugins: [],
};

export default config;
