

require('./bootstrap');

window.Vue = require('vue');

import VueProgressBar from 'vue-progressbar'
import Snotify, { SnotifyPosition } from 'vue-snotify'
import { Form, HasError, AlertError } from 'vform'

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

window.Form = Form

const options = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}

Vue.use(Snotify, options)
Vue.use(VueProgressBar, VueProgressBarOptions)

const VueProgressBarOptions = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'left',
    inverse: false
  }


Vue.component('customer', require('./components/Customer.vue').default);
Vue.component('paginate', require('./components/Paginate.vue').default);



const app = new Vue({
    el: '#app'
});
