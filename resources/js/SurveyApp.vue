<template>
    <div class="container-fluid">
        <router-view :key="$route.fullPath"></router-view>
        <!-- Could put last / next buttons here, and bind them to a url in the stor which gets updated for each page -->
    </div>
</template>

<script>

    import { mapActions, mapMutations, mapGetters } from 'vuex';

    export default {
        name: 'SurveyApp',
        methods: {
            ...mapActions(['initAppSettings']),
        },
        mounted() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            this.initAppSettings(survey_settings);
        }
    }
    /**
     * TODO: If user resumes finished survey take them to the final page
     * TODO: If user selects no legal, then we need to reset all legal answers (i.e. delete them)
     */
</script>

<style scoped>

</style>
