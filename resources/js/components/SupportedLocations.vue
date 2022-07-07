<template>
    <div class="container pt-2">

        <div v-if="getLocationDistText" class="col-12 col-md-10 offset-md-1 my-3">
            <div v-html="getLocationDistText" class="text-color p-2 mt-3" style="background-color: white; border: 1px solid black; border-radius: 5px; box-shadow: 0px 0px 50px -15px;"></div>
        </div>

        <div class="col-12 col-md-10 offset-md-1">
            <div v-for="(location, index) in locations" :key="location.id" class="row mt-3 justify-center">
                <div class="col-5 text-white">
                    <h3 class="text-white">{{ location.name }}</h3>
                </div>
                <div class="col-4">
                    <!-- <range-slider :initialValue="location.answer" :currentPercentage="locationDistPercentage" @input="changeAnswer($event, index)" /> -->
                    <new-slider :initialValue="location.answer" :currentPercentage="locationDistPercentage" @input="changeAnswer($event, index)"></new-slider>
                </div>
                <div class="col-3 col-lg-1 my-auto text-center p-0">
                    <h5 class="text-white">{{ location.answer }} %</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-right">
                    <h6 class="text-white">Total: {{ locationDistPercentage }}%</h6>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-10 offset-md-1 my-auto">
            <div class="row pb-2">
                <div class="col-12 col-md-4">
                    <!-- <button @click="gotoParent" class="py-2 px-3 btn bg-white rl-fg-blue h3 rounded"><span class="fas fa-times-circle"></span> These Don't Apply</button> -->
                </div>

                <div class="col-12 mt-3 mt-md-0 col-md-4 text-center">
                    <button @click="saveAndExit" class="py-2 px-3 mr-2" style="color: black; background-color: white; border: solid black 1px; cursor: pointer; border-radius: 5px;">Save & Exit</button>
                    <!-- <button v-if="locationDistPercentage == 100" @click="nextPage" class="py-2 px-3 ml-2" style="color: white; background-color: #008EC1; cursor: pointer; border-radius: 5px;">Continue</button> -->
                    <button @click="nextPage" class="py-2 px-3 ml-2" style="color: white; background-color: #008EC1; cursor: pointer; border-radius: 5px;">Continue</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import { mapActions, mapGetters, mapMutations } from 'vuex';
    import RangeSlider from './RangeSlider.vue';
    import NewSlider from './NewSlider.vue';
    import PositionIndicator from './PositionIndicator.vue';

    export default {
        name: 'SupportedLocations',
        components: {
            RangeSlider,
            NewSlider,
            PositionIndicator,
        },
        methods: {
            ...mapActions(['saveLocations', 'updateLocationAnswer', 'resetLocationQuestions']),
            ...mapMutations(['moveToNextBranch']),
            changeAnswer(newAnswer, answerIndex) {
                console.log(newAnswer)
                this.updateLocationAnswer({ newAnswer, answerIndex });
                this.currentPercentage = this.currentPercentage - newAnswer;
            },
            gotoParent() {
                this.resetLocationQuestions()
                    .then(function(){
                        this.$router.push({ name: 'AnnualHours' });
                    }.bind(this))

            },
            saveAndExit() {

                this.saveLocations()
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
                                localStorage.clear();
                                window.location.href = `/survey/landing?sv=${survey_hash}&ac=${code_hash}`;
                            }
                        });
                    })
                    .catch(function(data){
                        console.log(data);
                    });
            },
            nextPage() {
                if (this.locationDistPercentage != 100) {
                    Swal.fire({
                        title: 'Please fill to 100%',
                        text: 'Your selections must be total 100%.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                } else {
                    const that = this; // capture a reference to the current scope (could use call(), apply(), bind(), or even an arrow function, but hey this is simple enough)
    
                    this.saveLocations()
                        .then(() => {
                            if(that.showLegal)
                                that.$router.push({ name: 'HasLegal' });
                            else {
                                that.moveToNextBranch();
                                that.$router.push({ name: 'BranchIntro' })
                            }
                        })
                        .catch(function(data){
                            console.log(data);
                        });
                }
                    

            },
            lastPage() {
                this.$router.go(-1);
            },
        },
        computed: {
            ...mapGetters(['getLocationDistText', 'locations', 'locationDistPercentage']),
        },
    }
</script>

<style scoped>

</style>
