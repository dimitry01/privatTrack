require('./bootstrap');

import Vue from 'vue'
import App from './App.vue'
import Vuesax from 'vuesax'
import VueSweetalert2 from 'vue-sweetalert2';
import 'material-icons/iconfont/material-icons.css' //Material Icons
import './themeConfig.js'
import './globalComponents.js'
import router from './router'
import store from './store/store'
import i18n from './i18n/i18n'
import './filters/filters'
import {initialize} from './general';
import VeeValidate from 'vee-validate';
import 'prismjs'
import 'prismjs/themes/prism.css'
import VueClipboard from 'vue-clipboard2'
 
Vue.use(VeeValidate)
Vue.use(Vuesax)
Vue.use(VueSweetalert2)
Vue.use(VueClipboard)

initialize(store, router);

require('./assets/css/iconfont.css')

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    i18n,
    render: h => h(App)
}).$mount('#app')