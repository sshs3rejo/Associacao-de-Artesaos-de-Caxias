/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: '#7a2f1f',
                'brand-light': '#8b5a3c',
                'brand-dark': '#6a281a',
                accent: '#F9F7D3',
                'accent-hover': '#f0eeb0',
                price: '#c85a3a',
            },
        },
    },
    safelist: [
        'min-w-[44px]',
        'min-h-[44px]',
    ],
    plugins: [],
}
