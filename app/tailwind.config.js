/** @type {import('tailwindcss').Config} */
export default {
	theme: {
		extend: {
			fontFamily: {
				'mono': ['monospace'],
			},
		},
	},
	plugins: [],
	content: ['./public/**/*.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
}