/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          navy: '#0A1F3D',
          'navy-deep': '#061528',
          'navy-soft': '#132A4A',
          yellow: '#F5C400',
          'yellow-hover': '#E0B000',
          'yellow-soft': '#FFF4C2',
          ink: '#0A1F3D',
          muted: '#6B7280',
          line: '#E5E7EB',
          soft: '#F5F7FA',
        },
        primary: {
          50: '#eef3f9',
          100: '#d6e2ef',
          200: '#adc5df',
          300: '#7a9fc5',
          400: '#4a74a3',
          500: '#0A1F3D',
          600: '#081830',
          700: '#061224',
          800: '#040c18',
          900: '#02070d',
        },
        secondary: {
          50: '#fffbeb',
          100: '#fff4c2',
          200: '#ffe88a',
          300: '#ffd84d',
          400: '#f5c400',
          500: '#F5C400',
          600: '#E0B000',
          700: '#c99600',
          800: '#a37a00',
          900: '#7a5b00',
        },
        accent: {
          50: '#fffbeb',
          100: '#fff4c2',
          200: '#ffe88a',
          300: '#ffd84d',
          400: '#f5c400',
          500: '#F5C400',
          600: '#E0B000',
          700: '#c99600',
          800: '#a37a00',
          900: '#7a5b00',
        }
      },
      fontFamily: {
        sans: ['"Golos Text"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        display: ['Onest', '"Golos Text"', 'ui-sans-serif', 'sans-serif'],
      },
      borderRadius: {
        brand: '6px',
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-in-out',
        'slide-up': 'slideUp 0.6s ease-out',
        'bounce-slow': 'bounce 3s infinite',
        'pulse-slow': 'pulse 4s infinite',
        'float': 'float 6s ease-in-out infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideUp: {
          '0%': { transform: 'translateY(30px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        }
      },
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        'large': '0 10px 40px -10px rgba(0, 0, 0, 0.1), 0 2px 10px -2px rgba(0, 0, 0, 0.04)',
        'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        'brand': '0 8px 24px rgba(10, 31, 61, 0.12)',
      }
    },
  },
  plugins: [],
}
