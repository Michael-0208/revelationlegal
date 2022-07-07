<template>
    <div class="container-fluid px-0">
        <progress-bar :progress="percentComplete" />

        <div class="row mt-2">
            <div class="col-12">
                <span v-for="(segment, index) in segments" :key="segment" style="font-size: 1em; color: white; vertical-align: middle;" class="ml-1"><b>{{ segment }}</b> <span style="vertical-align: middle;" class="fa fa-chevron-right" v-if="index + 1 < segments.length"></span></span>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import ProgressBar from './ProgressBar.vue';

    export default {
        name: 'PositionIndicator',
        computed: {
            ...mapGetters(['segments', 'percentComplete']),
        },
        components: {
            ProgressBar
        }
    }
</script>
