/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#7a2f1f',
                    light: '#8b5a3c',
                    dark: '#6a281a',
                },
                accent: {
                    DEFAULT: '#F9F7D3',
                    hover: '#F2EB85',
                },
                price: '#c85a3a',
            },
            fontFamily: {
                sans: ['system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Noto Sans', 'Liberation Sans', 'Arial', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
