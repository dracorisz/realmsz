/** @type {import('tailwindcss').Config} */

module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
  safelist: [
    {
      pattern: /bg-(dragon|black|white|primary|accent|warning|success)|border-(dragon|black|white|primary|accent|warning|success)|text-(dragon|black|white|primary|accent|warning|success)/,
      variants: ["hover", "focus"],
    },
  ],
  theme: {
    extend: {
      aspectRatio: {
        'auto': 'auto',
        'square': '1 / 1',
        'video': '16 / 9',
      },
      height: {
        'ah': 'var(--ah)',
      },
      width: {
        'aw': 'var(--aw)',
      },
      minHeight: {
        'ah': 'var(--ah)',
      },
      minWidth: {
        'aw': 'var(--aw)',
      },
      maxHeight: {
        'ah': 'var(--ah)',
      },
      maxWidth: {
        'aw': 'var(--aw)',
      },
      colors: {
        accent: {
          50: '#f5f3ff',
          100: '#ede9fe',
          200: '#ddd6fe',
          300: '#c4b5fd',
          400: '#a78bfa',
          500: '#8b5cf6',
          600: '#7c3aed',
          700: '#6d28d9',
          800: '#5b21b6',
          900: '#4c1d95',
          950: '#2e1065',
        },
        dragon: {
          primary: {
            50: '#f0f9ff',
            100: '#e0f2fe',
            200: '#bae6fd',
            300: '#7dd3fc',
            400: '#38bdf8',
            500: '#0ea5e9',
            600: '#0284c7',
            700: '#0369a1',
            800: '#075985',
            900: '#0c4a6e',
            950: '#082f49',
          },
          secondary: {
            50: '#f0fdfa',
            100: '#ccfbf1',
            200: '#99f6e4',
            300: '#5eead4',
            400: '#2dd4bf',
            500: '#14b8a6',
            600: '#0d9488',
            700: '#0f766e',
            800: '#115e59',
            900: '#134e4a',
            950: '#042f2e',
          },
          dark: {
            50: '#f8fafc',
            100: '#f1f5f9',
            200: '#e2e8f0',
            300: '#cbd5e1',
            400: '#94a3b8',
            500: '#64748b',
            600: '#475569',
            700: '#334155',
            800: '#1e293b',
            900: '#0f172a',
            950: '#020617',
          },
        },
      },
      borderColor: {
        'dragon': {
          DEFAULT: 'rgba(45, 212, 191, 0.1)',
          'hover': 'rgba(45, 212, 191, 0.3)',
          'primary': {
            50: '#f0f9ff',
            100: '#e0f2fe',
            200: '#bae6fd',
            300: '#7dd3fc',
            400: '#38bdf8',
            500: '#0ea5e9',
            600: '#0284c7',
            700: '#0369a1',
            800: '#075985',
            900: '#0c4a6e',
            950: '#082f49',
          },
          'secondary': {
            50: '#f0fdfa',
            100: '#ccfbf1',
            200: '#99f6e4',
            300: '#5eead4',
            400: '#2dd4bf',
            500: '#14b8a6',
            600: '#0d9488',
            700: '#0f766e',
            800: '#115e59',
            900: '#134e4a',
            950: '#042f2e',
          },
          'dark': {
            50: '#f8fafc',
            100: '#f1f5f9',
            200: '#e2e8f0',
            300: '#cbd5e1',
            400: '#94a3b8',
            500: '#64748b',
            600: '#475569',
            700: '#334155',
            800: '#1e293b',
            900: '#0f172a',
            950: '#020617',
          },
        },
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
        'display': ['Poppins', 'ui-sans-serif', 'system-ui'],
      },
      animation: {
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'float': 'float 6s ease-in-out infinite',
        'glow': 'glow 2s ease-in-out infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        glow: {
          '0%, 100%': { 
            'text-shadow': '0 0 10px #2dd4bf, 0 0 20px #2dd4bf, 0 0 30px #2dd4bf',
            'box-shadow': '0 0 10px #2dd4bf, 0 0 20px #2dd4bf, 0 0 30px #2dd4bf',
          },
          '50%': { 
            'text-shadow': '0 0 20px #2dd4bf, 0 0 30px #2dd4bf, 0 0 40px #2dd4bf',
            'box-shadow': '0 0 20px #2dd4bf, 0 0 30px #2dd4bf, 0 0 40px #2dd4bf',
          },
        },
      },
      backgroundImage: {
        'dragon-pattern': "url('/images/dragon-pattern.svg')",
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-dragon': 'linear-gradient(45deg, var(--tw-gradient-stops))',
      },
      boxShadow: {
        'dragon': '0 4px 6px -1px rgba(45, 212, 191, 0.1), 0 2px 4px -1px rgba(45, 212, 191, 0.06)',
        'dragon-lg': '0 10px 15px -3px rgba(45, 212, 191, 0.1), 0 4px 6px -2px rgba(45, 212, 191, 0.05)',
        'dragon-xl': '0 20px 25px -5px rgba(45, 212, 191, 0.1), 0 10px 10px -5px rgba(45, 212, 191, 0.04)',
      },
    },
  },
};

