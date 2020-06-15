/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

window.Vue = require('vue');

import router from './router/index';
import store from './store/index';
import BootstrapVue from 'bootstrap-vue';
import _ from 'lodash';
import { Tabs, Tab } from 'vue-tabs-component';
import { Datetime } from 'vue-datetime';
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css';
import Select2 from 'v-select2-component';
import VueMoment from 'vue-moment';
import moment from 'moment-timezone';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faSpinner } from "@fortawesome/free-solid-svg-icons";
import { faEdit } from "@fortawesome/free-solid-svg-icons";
import { faTrashAlt } from "@fortawesome/free-solid-svg-icons";
import { faEye } from "@fortawesome/free-solid-svg-icons";
import { faEyeSlash } from "@fortawesome/free-solid-svg-icons";
import Auth from './mixins/auth';
import AuthId from './mixins/auth';
import Permissions from './mixins/permissions';
import fieldAccessMixin from './mixins/fieldAccessMixin';

Vue.mixin(Auth);
Vue.mixin(AuthId);
Vue.mixin(Permissions);
Vue.mixin(fieldAccessMixin);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('index-component', require('./components/IndexComponent.vue').default);
Vue.component('dashboard-component', require('./components/DashboardComponent.vue').default);
Vue.component('tabs-users-component', require('./components/users/TabsComponent.vue').default);
Vue.component('tabs-roles-component', require('./components/roles/TabsComponent.vue').default);
Vue.component('tabs-categories-component', require('./components/categories/TabsComponent.vue').default);
Vue.component('tabs-expenses-component', require('./components/expenses/TabsComponent.vue').default);
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);
Vue.component('edit-role', require('./components/roles/EditComponent.vue').default);
Vue.component('edit-user', require('./components/users/EditComponent.vue').default);
Vue.component('edit-category', require('./components/categories/EditComponent.vue').default);
Vue.component('edit-expense', require('./components/expenses/EditComponent.vue').default);
Vue.component('actions', require('./components/widgets/utilities/ActionsComponent.vue').default);
Vue.component('datetime', Datetime);
Vue.component('Select2', Select2);
Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(BootstrapVue);

Vue.use(VueMoment, {
  moment
});

// Configure lodash
Vue.prototype._ = _;

// Add Fontawesome icons
library.add([
  faSpinner,
  faEdit,
  faTrashAlt,
  faEye,
  faEyeSlash
]);

// Filters
Vue.filter('ellipsis', function (value) {
  return (value && value.length > 20) 
  ? `${value.substring(0, 20)}...` 
  : value;
});

Vue.filter('capitalize', function (value) {
  if (!value) return ''
  return value.replace(/(^\w{1})|(\s{1}\w{1})/g, match => match.toUpperCase());
});

Vue.filter('underscoreToSpace', function (value) {
  return value.replace(/_/ig, ' ');
});

// Register a global custom directive called `v-focus`
Vue.directive('focus', {
  // When the bound element is inserted into the DOM...
  inserted: function (el) {
    // Focus the element
    el.focus()
  }
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  router,
  store,
  el: '#app',
});
