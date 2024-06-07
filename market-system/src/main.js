import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import BootstrapVue3 from 'bootstrap-vue-3';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';

import { loadFonts } from './plugins/webfontloader';

loadFonts();

const app = createApp(App);
app.use(router);
app.use(BootstrapVue3);

app.config.globalProperties.$formatDate = (value) => {
  if (value) {
    return new Date(value).toLocaleDateString('pt-BR');
  }
  return null;
};

app.mount('#app');

