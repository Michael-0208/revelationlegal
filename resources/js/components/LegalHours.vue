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
            <div v-html="$store.getters.getAnnualLegalHoursText" class="col-12 text-white py-2"></div>
        </div>

        <div class="row mb-3">
            <div class="col text-white">
                <input type="number" @change="setLegalHours" :value="legalHours" class="form-control"/>
                <span class="float-left">Legal Hours Worked Per Year</span>
            </div>
        </div>

        <div class="row mt-3">
            <!--<div class="col-6 text-right">
                <router-link :to="{ name: 'HasLegal' }" class="btn bg-white rl-fg-blue h3 rounded">Back</router-link>
            </div>-->
            <div class="col-6 text-left">
                <button @click="nextPage" class="btn h3 text-white btn-revelation-primary rounded">Continue</button>
            </div>
        </div>

    </div>
</template>

<script>

    import { mapActions, mapMutations, mapGetters } from 'vuex';
import Button from '../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/Button.vue';
    import NavSlot from './slots/NavSlot.vue';

    export default {
        name: 'HasLegal',
        components: {
            NavSlot,
                Button
        },
        beforeRouteLeave(to, from, next) {
            this.saveLegalHours()
                .then(next)
                .catch(function(data){
                    console.log(data);
                })
        },
        methods: {
            ...mapMutations(['updateLegalHours']),
            ...mapActions(['saveLegalHours']),
            setLegalHours(event) {
                this.updateLegalHours(event.target.value);
            },
            nextPage() {
                if (this.legalHours > 0 && this.legalHours <= this.yearlyHours) {
                    this.$router.push({ name: 'BranchIntro' });
                } else {
                    Swal.fire({
                        title: 'Validate Legal Hours',
                        text: `Legal hours must be greater than 0 and less than or equal to your total annual hours (${this.yearlyHours}).`,
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                }
            }
        },
        computed: {
            ...mapGetters(['showLegal', 'legalHours', 'yearlyHours']),
            isValidated() {
                return this.legalHours > 0 && this.legalHours <= this.yearlyHours;
            }
        }
    }
</script>

<style scoped>

</style>
