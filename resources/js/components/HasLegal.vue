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

        <div class="row mb-1">
            <div v-html="$store.getters.getShowLegalYNText" class="col-12 text-white py-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col text-white">
                <label class="form-control">
                    <strong>Yes</strong>
                    <input type="radio" :checked="showLegal === 1" @change="updateShowLegal(1)" />
                </label>
                <label class="form-control">
                    <strong>No</strong>
                    <input type="radio" :checked="showLegal === 0" @change="updateShowLegal(0)" />
                </label>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6 text-right">
                <router-link :to="{ name:'AnnualHours' }" class="btn bg-white rl-fg-blue h3 rounded">Back</router-link>
            </div>
            <div class="col-6 text-left">
                <button @click="nextPage" class="btn h3 text-white btn-revelation-primary rounded">Next</button>
            </div>
        </div>
    </div>
</template>

<script>

    import { mapActions, mapMutations, mapGetters } from 'vuex';

    export default {
        name: 'HasLegal',
        components: {
        },
        created() {
        },
        methods: {
            ...mapMutations(['updateShowLegal']),
            ...mapActions(['getSurveyBranches', 'resetLegalAnswers']),
            nextPage() {
                const app = this;

                if(!this.showLegal)
                    this.resetLegalAnswers()
                        .then(function(data){
                            app.gotoNextPage();
                        })
                        .catch(function(data){
                            console.log(data);
                        })
                else
                    this.gotoNextPage();
            },
            gotoNextPage() {
                const currentBranch = this.currentBranch;
                if(!this.showLegal && currentBranch)
                    this.$router.push({ name: 'BranchIntro' });
                else
                    this.$router.push({ name: 'LegalHours' });
            }
        },
        computed: {
            ...mapGetters(['showLegal', 'currentBranch']),
        }
    }
</script>

<style scoped>

</style>
