import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp } from 'vue';
const app = createApp({});

const files = require.context('./', true, /\.vue$/i)
console.log(files);
files.keys().map(key => app.component(key.split('/').pop().split('.')[0], files(key).default))
app.mount('#app');