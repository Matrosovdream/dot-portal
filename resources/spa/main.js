import { createApp } from 'vue';
import { createPinia } from 'pinia';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import Tooltip from 'primevue/tooltip';
import Ripple from 'primevue/ripple';

import App from '@/App.vue';
import router from '@/router';
import { DotPreset } from '@/theme/preset';

import 'primeicons/primeicons.css';
import '@/assets/styles/main.scss';

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(PrimeVue, {
    ripple: true,
    theme: {
        preset: DotPreset,
        options: {
            darkModeSelector: '.app-dark',
            cssLayer: false,
        },
    },
});
app.use(ToastService);
app.use(ConfirmationService);

app.directive('tooltip', Tooltip);
app.directive('ripple', Ripple);

app.mount('#app');
