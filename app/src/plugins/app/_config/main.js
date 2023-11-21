import { createApp } from 'vue'
import App from './App.vue'

import router from './router'

import '../_themes/tailwind.css'

import { createI18n } from "vue-i18n";  
import en from "@/plugins/app/_locales/en.json";

import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

const app = createApp(App);
const i18n = createI18n({
    locale: "en",
    fallbackLocale: "en",
    messages: { en },
});
	
app.use(router).use(i18n).use(ToastPlugin).mount('#app');