<template>
    <div class="container-fluid py-3">

        <div class="row mt-3">
            <div class="col-12 col-md-10 offset-md-1">
                <position-indicator />
            </div>


            <div class="col-12 col-md-10 offset-md-1 my-3">
                <div v-html="pageDescription" class="text-color p-2 mt-3" style="background-color: white; border: 1px solid black; border-radius: 5px; box-shadow: 0px 0px 50px -15px;"></div>
            </div>

            <div class="d-none d-md-flex col-1">
                <!--Here to keep the next back arrow column from wrapping onto parent line-->
            </div>

            <div class="col-12 col-md-1 my-auto text-left">
                <button @click="lastQuestion" class="text-white fa-2x fa fa-chevron-left d-none d-md-block" style="position: fixed; top: 45vh;"></button>
            </div>

            <div class="col-12 col-md-10">
                <div v-for="question in questions" :key="question.id" class="row mt-2 mb-2 border-bo">
                    <div class="col-12">
                        <div class="row border-bottom pb-2">
                            <div class="col-6">
                                <h3 class="text-white">{{ question.label }}</h3> 
                                <a class="text-white" href="javascript:void(0);" @click="displayDefinition(question.label, question.description)">Read More</a>
                            </div>
                            <div class="col-4 col-lg-5 my-auto text-right">
                                <!-- <input type="number" :value="question.answer" @change="changeAnswer($event, question.id)" /> -->
                                <!-- <range-slider :initialValue="question.answer" :currentPercentage="totalPercentage" @input="changeAnswer($event, question.id)" /> -->
                                <new-slider :initialValue="question.answer" :currentPercentage="totalPercentage" @input="changeAnswer($event, question.id)"></new-slider>
                            </div>
                            <div class="col-2 col-lg-1 my-auto text-center">
                                <h5 class="text-white">{{ question.answer }} %</h5>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-12">
                        <h6 class="text-white border-bottom pb-3">{{ question.description }}</h6>
                    </div> -->
                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <h6 class="text-white">Total: {{ totalPercentage }}%</h6>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-1 my-auto text-right">
                <button v-if="canContinue" @click="nextQuestion" class="text-white fa-2x fa fa-chevron-right d-none d-md-block" :disabled="!canContinue" style="position: fixed; top: 45vh;"></button>
            </div>

            <div class="col-12 col-md-10 offset-md-1 my-auto">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <button @click="gotoParent" class="py-2 px-3" style="color: #BD3245; background-color: white; border: solid #BD3245 1px; cursor: pointer; border-radius: 5px;"><span class="fas fa-times-circle"></span> These Don't Apply</button>
                    </div>

                    <div class="col-12 mt-3 mt-md-0 col-md-4 text-center">
                        <button @click="saveAndExit" class="py-2 px-3 mr-2 btn bg-white rl-fg-blue rounded mb-0">Save & Exit</button>
                        <button @click="nextQuestion" class="py-2 px-3 ml-2 btn" style="color: white; background-color: #008EC1; cursor: pointer; border-radius: 5px;">Continue</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    import { mapActions, mapGetters } from 'vuex';
    import NavSlot from './slots/NavSlot.vue';
    import VueSlideBar from 'vue-slide-bar';
    import RangeSlider from './RangeSlider.vue';
    import NewSlider from './NewSlider.vue';
    import PositionIndicator from './PositionIndicator.vue';

    export default {
        name: 'SurveyQuestion',
        created() {
            this.getCurrentQuestion({ question_id: this.$route.params.question_id });
        },
        components: {
            NavSlot,
            VueSlideBar,
            RangeSlider,
            NewSlider,
            PositionIndicator
        },
        methods: {
            ...mapActions(['saveState', 'updateAnswer', 'resetQuestions']),
            ...mapActions(['getCurrentQuestion']),
            changeAnswer(newAnswer, questionId) {
                this.updateAnswer({ newAnswer, questionId });
            },
            displayDefinition (title, description) {
                Swal.fire({
                    titleText: title,
                    html: `<p style="text-align:left;">${description}</p>`,
                    width: 850,
                    confirmButtonText: 'OK'
                });
            },
            gotoParent() {
                this.resetQuestions()
                    .then(function(){
                        if(this.parentQuestionId == 0) {

                            if(this.currentBranch.name.toLowerCase().includes('support'))
                                this.$router.push({ name: 'AnnualHours' });
                            else
                                this.$router.push({ name: 'LegalHours' });

                        } else {
                                this.$router.push({ name: 'questions', params: { question_id: this.parentQuestionId } });
                        }
                    }.bind(this))

            },
            saveAndExit() {

                this.saveState()
                    .then(function(data){
                        Swal.fire({
                            title: 'Finish Questionnaire Later',
                            text: 'Your responses are saved. When ready, please return to the survey through the email invitation you received.',
                            icon: 'success',
                            showCancelButton: true,
                            reverseButtons: true,
                            confirmButtonColor: '#286090',
                            cancelButtonColor: '#6C757D',
                            cancelButtonText: 'Return to Questionnarie',
                            confirmButtonText: 'Exit Questionnarie'
                        })
                        .then(function(result){
                            if (result.isConfirmed) {
                                // localStorage.clear();
                                window.location.href = `/survey/landing?sv=${survey_hash}&ac=${code_hash}`;
                            }
                        });
                    })
                    .catch(function(data){
                        console.log(data);
                    });
            },
            nextQuestion() {
                if (this.canContinue) {
                    const that = this; // capture a reference to the current scope (could use call(), apply(), bind(), or even an arrow function, but hey this is simple enough)
    
                    this.saveState()
                        .then((next) => {
                            this.$router.push(next)
                        })
                        .catch(function(data){
                            console.log(data);
                        });
                } else {
                    Swal.fire({
                        title: 'Please fill to 100%',
                        text: 'Your responses must be total 100%.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                }

            },
            lastQuestion() {
                this.$router.go(-1);
            },
        },
        computed: {
            ...mapGetters(['pageDescription', 'questions', 'currentQuestionId', 'canContinue', 'totalPercentage', 'parentQuestionId', 'currentPosition', 'currentBranch']),
        },
    }
</script>

<style scoped>

</style>
