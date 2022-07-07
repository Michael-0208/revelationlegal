<template>
    <div class="container">

        <div class="row">
            <div class="col-12">
                <div class="text-color p-2 mt-3" style="background-color: white; border: 1px solid black; border-radius: 5px; box-shadow: 0px 0px 50px -15px;">
                    This is a simple calculation to estimate the total hours you work each year.
                    The objective is to capture ALL your time (regular, weekend, evening, travel, etc.)
                    devoted to either practicing or supporting the practice. This form will assist you in
                    allocating your hours into each of the two branches of activities (Legal Services and Support Services).
                </div>
            </div>
        </div>

        <div class="row">
            <div v-html="$store.getters.getWeeklyHoursText" class="col-12 text-white py-2"></div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <strong class="text-white">Number of hours per week</strong>
                <input type="number" @keyup="setWeeklyHours($event)" :value="weeklyHours" class="form-control" />
            </div>
            <div class="col-12 col-md-6">
                <strong class="text-white">Number of hours per year</strong>
                <input type="number" @change="setYearlyHours($event)" :value="yearlyHours" class="text-white" style="border: none; display: block; width: 100%; background-color: #47525D;" />
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6 text-right">
                <router-link :to="{ name:'StartSurvey' }" class="btn bg-white rl-fg-blue h3 rounded">Back</router-link>
            </div>
            <div class="col-6 text-left">
                <button @click="nextPage" class="btn h3 text-white btn-revelation-primary rounded">Next</button>
            </div>
        </div>
    </div>
</template>

<script>

    import { mapActions, mapGetters, mapMutations } from 'vuex';

    export default {
        name: 'AnnualHours',
        beforeRouteLeave(to, from, next) {
            this.saveAnnualHours()
                .then(next)
                .catch(function(data){
                    console.log(data);
                });
        },
        methods: {
            ...mapActions(['updateWeeklyHours', 'updateYearlyHours', 'saveAnnualHours']),
            ...mapMutations(['moveToNextBranch']),
            setWeeklyHours(event) {
                this.updateWeeklyHours({ hours: event.target.value });
            },
            setYearlyHours(event) {
                this.updateYearlyHours({ hours: event.target.value });
            },
            nextPage() {
                if(this.getShowLocationDist)
                    this.$router.push({ name: 'LocationDistribution' });
                else if(this.getShowLegalServices)
                    this.$router.push({ name: 'HasLegal' });
                else {
                    this.moveToNextBranch();
                    this.$router.push({ name: 'BranchIntro' })
                }
            }
        },
        computed: {
            ...mapGetters(['weeklyHours', 'yearlyHours', 'supportBranch', 'showLegal', 'getShowLocationDist', 'getShowLegalServices']),
        }
    }
</script>

<style scoped>

</style>
