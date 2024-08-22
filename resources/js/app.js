import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { plugin as VueTippy } from 'vue-tippy';
import VueTheMask from 'vue-the-mask';
import "tippy.js/dist/tippy.css";
import "tippy.js/themes/light.css";
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { VueToggles } from "vue-toggles";
import money from 'v-money';

library.add(fas, far, fab)

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ToastPlugin)
            .use(VueTheMask)
            .use(money)
            .component('font-awesome-icon', FontAwesomeIcon)
            .component("v-select", vSelect)
            .component('vue-toggle', VueToggles)
            .use(VueTippy, {directive: 'tippy', component: 'tippy'})
            app.config.globalProperties.$filters = {
                federalDocument(value) {
                    if (!value) {
                        return;
                    }
                    value = value.replace(/[^0-9*]/g, '');
                    if(value.length === 14) {
                        return value.replace(/(\S{2})?(\S{3})?(\S{3})?(\S{4})?(\S{2})/, "$1.$2.$3/$4-$5");
                    }
                   
                    return value;
                },
                phone(value) {
                    if (!value) {
                        return;
                    }
                    value = value.replace(/[^0-9*]/g, '');
                    if(value.length === 8) {
                        return value.replace(/(\S{4})?(\S{4})/, "$1-$2");
                    }
                    if(value.length === 10) {
                        return value.replace(/(\S{2})?(\S{4})?(\S{4})/, "($1) $2-$3");
                    }
                    if(value.length === 11) {
                        return value.replace(/(\S{2})?(\S{5})?(\S{4})/, "($1) $2-$3");
                    }
                   
                    return value;
                },
                dateFormat: function(date){
                    date = new Date(date);
                    date = date.setHours(date.getHours() -3);
                    return new Date(date).toLocaleString().replace(',', '');
                },
                priceFormat: function(price){
                    if (!price) {
                        return;
                    }
                    return price.replace(".", ",");
                }
            }
            app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});