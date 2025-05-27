import './bootstrap';

import Alpine from 'alpinejs';


// import { createApp } from 'vue'


const app = createApp({})
app.component('asiento-editor', AsientoEditor)
app.mount('#app')

window.Alpine = Alpine;

Alpine.start();
