/** @type {import('tailwindcss').Config} */

module.exports = {
  darkMode: "selector",
  content: ["./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php", "./vendor/laravel/jetstream/**/*.blade.php", "./storage/framework/views/*.php", "./resources/views/**/*.blade.php", "./resources/js/**/*.vue"],
  plugins: [
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
    function ({ matchUtilities, theme }) {
      matchUtilities(
        {
          aw: (value) => ({
            width: value,
            maxWidth: value,
            minWidth: value,
          }),
          ah: (value) => ({
            height: value,
            maxHeight: value,
            minHeight: value,
          }),
          as: (value) => ({
            width: value,
            maxWidth: value,
            minWidth: value,
            height: value,
            maxHeight: value,
            minHeight: value,
          }),
        },
        { values: theme("spacing") },
      );
    },
  ], 
  safelist: [
    {
      pattern: /bg-(black|white|primary|accent|warning|success)|border-(black|white|primary|accent|warning|success)|text-(black|white|primary|accent|warning|success)/,
      variants: ["hover", "focus"],
    },
  ],
  theme: {
    extend: {
      fontFamily: {
        primary: ["Inter", "ui-sans-serif", "system-ui", "-apple-system", "BlinkMacSystemFont"],
      },
      animation: {
        jiggle: "jiggle .5s ease-in-out",
        grow: "grow .5s linear",
        fadeIn: "fadeIn .3s ease-in-out",
        wave: "wave .5s ease-in-out",
        spinPulse: "spin 50s linear infinite, pulse 30s ease-in-out infinite alternate"
      },
      keyframes: {
        jiggle: {
          '0%': { transform: 'rotate(5deg)' },
          '50%': { transform: 'rotate(-5deg)' },
          '100%': { transform: 'rotate(0deg)' },
        },
        grow: {
          '0%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.15)' },
          '100%': { transform: 'scale(1.13)' },
        },
        wave: {
          '0%': { transform: 'rotate(5deg); scale(1); translateX(0px);' },
          '50%': { transform: 'rotate(-5deg); scale(1.2); translateX(10px); translateY(-20px);' },
          '100%': { transform: 'rotate(5deg); scale(1); translateX(0px);' },
        },
        fadeIn: {
          from: { opacity: 0, transform: 'translateY(10px)' },
          to: { opacity: 1, transform: 'translateY(0)' }
        }
      },
      fontSize: {
        zx: '10px'
      },
    },
    colors: {
      transparent: "transparent",
      current: "currentColor",
      black: "#000000",
      white: "#ffffff",
      primary: "#00449f",
      accent: "#00c2c5",
      warning: "#ff0033",
      success: "#22c55e",
    },
  },
};

