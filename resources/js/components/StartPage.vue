<template>
    <div class="container text-white p-3">
        <div v-html="$store.getters.getBeginPage" class="container-fluid"></div>
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button @click="resetSurvey" class="btn rounded bg-white rl-fg-blue mx-1" :disabled="!$store.state.branches.length" ><strong>{{ $store.state.survey_start_dt !== null || $store.state.survey_last_dt !== null ? "Review my previous answers" : "Start Survey"  }}</strong></button>
                    <router-link :to="{ name: 'questions', params: {question_id: currentQuestionId } }" v-if="currentQuestionId && $store.state.survey_completed != 1" class="btn h3 text-white btn-revelation-primary rounded mx-1 mt-2">Continue where I left off</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions, mapMutations } from 'vuex';

    export default {
        name: 'StartPage',
        created() {
            var locations = {};
            var branches = JSON.parse(localStorage.getItem('branches'));
            var currentQuestionId = parseInt(localStorage.getItem('current_question'));
            var parentQuestionId = parseInt(localStorage.getItem('parent_question'));
            var weeklyHours = parseInt(localStorage.getItem('weekly_hours'));
            var yearlyHours = parseInt(localStorage.getItem('yearly_hours'));
            var legalHours = parseInt(localStorage.getItem('legal_hours'));
            var showLegal = parseInt(localStorage.getItem('show_legal'));
            if (survey_progress !== null) {
                const settingJSON = JSON.parse(survey_progress.data);
                
                // try loading cached session into app state
                branches = JSON.parse(settingJSON.branches);
                locations = JSON.parse(settingJSON.locations);
                currentQuestionId = parseInt(settingJSON.current_question);
                parentQuestionId = parseInt(settingJSON.parent_question);
                weeklyHours = parseInt(settingJSON.weekly_hours);
                yearlyHours = parseInt(settingJSON.yearly_hours);
                legalHours = parseInt(settingJSON.legal_hours);
                showLegal = parseInt(settingJSON.show_legal);
            }
            const branchIndex = survey_settings.show_legal_services && showLegal ? 0 : 1;

            this.initializeSurveyStartDate({survey_start_dt, survey_last_dt});

            this.initializeSurveyCompleted({survey_completed});
            
            // if we have a valid cached session load it into the app's state
            if(branches && currentQuestionId && weeklyHours && yearlyHours) 
                return this.initializeState({
                    branches,
                    currentQuestionId,
                    parentQuestionId,
                    weeklyHours,
                    yearlyHours,
                    legalHours,
                    showLegal,
                    branchIndex,
                    survey_start_dt,
                    survey_last_dt,
                    survey_completed,
                    locations,
                });

            // else start with the branches
            this.getSurveyBranches()
                .catch(function(data){
                    console.log(data);
                });

        },
        computed: {
            ...mapGetters(['currentQuestionId']),
            respondentName() {
                return respondent.resp_first + ' ' + respondent.resp_last;
            },
        },
        methods: {
            ...mapActions(['startOver', 'getSurveyBranches']),
            ...mapMutations(['initializeState', 'initializeSurveyStartDate', 'initializeSurveyCompleted']),
            resetSurvey() {
                this.startOver()
                    .then(() => this.$router.push({ name: 'AnnualHours' }));
            }
        }
    }
</script>

<style scoped>
</style>
