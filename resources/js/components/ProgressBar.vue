<template>
    <div class="row mb-2" style="height: 2rem;">
        <div class="col-12 p-1 rounded" style="background-color: lightgrey; box-shadow: inset 0 0 5px 2px;">
            <div class="rounded" :style="{ width: width + '%' }" style="background-color: #008CC2; color: white; text-align: right; height: 1.5rem;"><span style="margin-right: 20px; font-size: 15px;">{{ progress }}%</span></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ProgressBar',
        props: ['progress'],
        data() {
            return {
                width: 0,
            }
        },
        created () {
            this.width = this.progress;
        },
        methods: {
            decrement() {
                if(this.width > this.progress)
                    this.width --;
                else
                    clearInterval();
            },
            increment() {
                if(this.width < this.progress)
                    this.width ++;
                else
                    clearInterval();
            }
        },
        watch: {
            progress() {

                if(this.width > this.progress) {
                    setInterval(this.decrement.bind(this), 50);
                }

                if(this.width < this.progress) {
                    setInterval(this.increment.bind(this), 10);
                }
            }
        }
    }
</script>
