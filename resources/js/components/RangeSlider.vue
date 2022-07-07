<template>
    <span>
        <div class="row" style="position:absolute;bottom:-8px;width:100%">
            <div class="col-6 text-center text-white text-xs" style="font-size:0.6rem;">25</div>
            <div class="col-6 text-center text-white text-xs" style="font-size:0.6rem;">75</div>
        </div>
        <div class="row" style="position:absolute;bottom:-8px;width:100%">
            <div class="col-12 text-center text-white text-xs" style="font-size:0.6rem;">50</div>
        </div>
        <div class="row"
            style="position:absolute;bottom:-8px;width:100%;display:flex;justify-content:space-between;padding-left:10px;">
            <div class="text-white text-xs" style="font-size:0.6rem;">0</div>
            <div class="text-white text-xs" style="font-size:0.6rem;">100</div>
        </div>
        <input type="range" v-model="newPercentage" @change="update" class="form-control" style="padding: 0px;" />
    </span>

</template>

<script>
export default {
    name: 'RangeSlider',
    props: ['initialValue', 'currentPercentage'],
    data() {
        return {
            newPercentage: this.initialValue,
            lastPercentage: this.initialValue,
        };
    },
    methods: {
        update() {

            const newPercentage = parseInt(this.newPercentage);
            const lastPercentage = parseInt(this.lastPercentage);
            const currentPercentage = parseInt(this.currentPercentage);
            const nextPercentage = newPercentage + (currentPercentage - lastPercentage); // substract out the this sliders previous value than add in the new value

            if (nextPercentage > 100)
                this.newPercentage = (100 - currentPercentage) + lastPercentage; // get the difference between 100 and the total percentage then add / include the last percentage

            this.$emit('input', parseInt(this.newPercentage));
            this.lastPercentage = this.newPercentage;
        }
    },
    computed: {
    }
}
</script>

<style>
</style>
