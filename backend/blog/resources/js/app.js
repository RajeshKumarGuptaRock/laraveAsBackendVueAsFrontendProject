import App from './components/App.vue';
import { createApp } from 'vue';
import router from './router';
import { createPinia } from 'pinia';

const app = createApp(App);

app.use(router);

app.use(createPinia());

app.mount('#app');