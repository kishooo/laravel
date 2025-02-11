import { createApp } from 'vue';
import App from './App.vue';
import axios from 'axios';
import router from './router'; 
// import '@/assets/tailwind.css';
import './assets/main.css'; 
// import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

// Import Bootstrap and BootstrapVue CSS files
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'bootstrap/dist/css/bootstrap.css';

axios.defaults.baseURL = 'http://localhost:8000/api';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = createApp(App);

// Make Axios globally available inside Vue components
app.config.globalProperties.$axios = axios;

// app.use(BootstrapVue);
// app.use(IconsPlugin);

app.use(router); 
app.mount('#app');
