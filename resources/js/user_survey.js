require('./bootstrap');

// import '../css/survey_styles.sass';

import Vue from 'vue'
import store from './store/survey_store.js';
import router from './routes/survey_router.js';

import NavSlot from './components/slots/NavSlot.vue';
import SurveyApp from './SurveyApp.vue';

Vue.component('nav-slot', NavSlot);

const app = new Vue({
    el: '#app',
    render: h => h(SurveyApp),
    store,
    router
});
