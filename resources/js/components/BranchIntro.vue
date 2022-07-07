<template>
    <div class="container py-3">
        <div class="row mt-3">
            <div class="col">
                <h1 class="text-white">{{ currentBranch.name }}</h1>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col text-white">
                <div v-html="branchDescription"></div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6 text-right">
                <button @click="saveAndExit" class="btn bg-white rl-fg-blue h3 rounded">Save and Exit</button>
            </div>
            <div class="col-6 text-left">
                <router-link :to="nextPage" class="btn h3 text-white btn-revelation-primary rounded">Continue</router-link>
            </div>
        </div>
    </div>
</template>

<script>

    import { mapActions, mapMutations, mapGetters } from 'vuex';
    import NavSlot from './slots/NavSlot.vue';

    export default {
        name: 'BranchIntro',
        methods: {
            ...mapMutations(['updateShowLegal']),
            ...mapActions(['getSurveyBranches']),
            saveAndExit() {
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
            },
        },
        components: {
            NavSlot,
        },
        computed: {
            ...mapGetters(['currentBranch', 'showLegal', 'yearlyHours', 'legalHours']),
            lastPage() {
                return this.showLegal ? { name: 'LegalHours' } : { name: 'AnnualHours' };
            },
            nextPage() {
                return { name: 'questions', params: { question_id: this.currentBranch.question_id } };
            },
            branchDescription() {
                if(this.currentBranch.name.includes("Legal"))
                    return this.currentBranch.description.replace('{hours}', this.legalHours);
                else
                    return this.currentBranch.description.replace('{hours}', this.yearlyHours);

            }
        }
    }
</script>

<style scoped>

</style>
