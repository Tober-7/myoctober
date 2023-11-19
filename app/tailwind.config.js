/** @type {import('tailwindcss').Config} */
export default {
	theme: {
		extend: {
			fontFamily: {
				'sans': ['monospace'],
			},
		},
	},
	plugins: [],
	content: ['./public/**/*.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
}