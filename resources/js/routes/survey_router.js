import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store/survey_store';

import StartPage from '../components/StartPage.vue';
import AnnualHours from '../components/AnnualHours.vue';
import SupportedLocations from '../components/SupportedLocations.vue';
import HasLegal from '../components/HasLegal.vue';
import LegalHours from '../components/LegalHours.vue';
import BranchIntro from '../components/BranchIntro.vue';
import SurveyQuestion from '../components/SurveyQuestion.vue';
import SurveyFinished from '../components/SurveyFinished.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    base: `/survey/questionnaire`,
    routes: [
        { path: '/', name: 'StartSurvey', component: StartPage },
        { path: '/annual-hours', name: 'AnnualHours', component: AnnualHours },
        { path: '/locations', name: 'LocationDistribution', component: SupportedLocations },
        { path: '/has-legal', name: 'HasLegal', component: HasLegal },
        { path: '/legal-hours', name: 'LegalHours', component: LegalHours },
        { path: '/branch-intro', name: 'BranchIntro', component: BranchIntro },
        { path: '/question/:question_id', name: 'questions', component: SurveyQuestion },
        { path: '/done', name: 'SurveyFinished', component: SurveyFinished },
    ]
});


router.beforeEach(function(to, from, next) {

    // check if the state is initialized and redirect to start if not

    const state = store.state;

    // the app is not initialized yet, redirect to start page
    if(to.name != 'StartSurvey' && !state.branches.length)
        return next({ name: 'StartSurvey'});

    // else continue to next page
    return next();

});

export default router;
