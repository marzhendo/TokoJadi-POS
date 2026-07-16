// tailwind.config.js
// Diterjemahkan dari DESIGN.md (Stitch export) — AgroFlow Utility design system
// ponytail: content paths dari Breeze dipertahankan agar semua view ter-scan

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        surface: {
          DEFAULT: '#f8f9fa',
          dim: '#d9dadb',
          bright: '#f8f9fa',
          'container-lowest': '#ffffff',
          'container-low': '#f3f4f5',
          container: '#edeeef',
          'container-high': '#e7e8e9',
          'container-highest': '#e1e3e4',
        },
        'on-surface': '#191c1d',
        'on-surface-variant': '#40493d',
        'inverse-surface': '#2e3132',
        'inverse-on-surface': '#f0f1f2',
        outline: '#707a6c',
        'outline-variant': '#bfcaba',
        primary: {
          DEFAULT: '#0d631b',
          container: '#2e7d32',
          on: '#ffffff',
          'on-container': '#cbffc2',
          inverse: '#88d982',
        },
        secondary: {
          DEFAULT: '#2a6b2c',
          container: '#acf4a4',
          on: '#ffffff',
          'on-container': '#307231',
        },
        tertiary: {
          DEFAULT: '#923357',
          container: '#b14b6f',
          on: '#ffffff',
          'on-container': '#ffedf0',
        },
        error: {
          DEFAULT: '#ba1a1a',
          on: '#ffffff',
          container: '#ffdad6',
          'on-container': '#93000a',
        },
        margin: {
          success: '#4CAF50',
          warning: '#FFB300',
          danger: '#D32F2F',
        },
        table: {
          border: '#E0E4E0',
        },
        text: {
          primary: '#1A1C1A',
          secondary: '#5F635F',
        },
      },
      fontFamily: {
        headline: ['Hanken Grotesk', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
        sans: ['Inter', 'sans-serif'], // fallback untuk komponen Breeze
      },
      fontSize: {
        'display-price': ['32px', { lineHeight: '40px', fontWeight: '700', letterSpacing: '-0.02em' }],
        'headline-lg': ['24px', { lineHeight: '32px', fontWeight: '600' }],
        'headline-lg-mobile': ['20px', { lineHeight: '28px', fontWeight: '600' }],
        'headline-md': ['20px', { lineHeight: '28px', fontWeight: '600' }],
        'body-lg': ['16px', { lineHeight: '24px', fontWeight: '400' }],
        'body-md': ['14px', { lineHeight: '20px', fontWeight: '400' }],
        'table-data': ['14px', { lineHeight: '20px', fontWeight: '500' }],
        'label-caps': ['12px', { lineHeight: '16px', fontWeight: '700', letterSpacing: '0.05em' }],
        'numeric-mono': ['14px', { lineHeight: '20px', fontWeight: '500' }],
      },
      borderRadius: {
        sm: '0.125rem',
        DEFAULT: '0.25rem',
        md: '0.375rem',
        lg: '0.5rem',
        xl: '0.75rem',
        full: '9999px',
      },
      spacing: {
        base: '4px',
        xs: '4px',
        sm: '8px',
        md: '16px',
        lg: '24px',
        xl: '40px',
        gutter: '16px',
        'margin-mobile': '12px',
        'margin-desktop': '32px',
      },
      maxWidth: {
        desktop: '1440px',
      },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};
