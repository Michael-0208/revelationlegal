<template>
  <div>
    <vue-slider
      v-model="newPercentage"
      :marks="marks"
      @dragging="update"
      @change="update"
      ref="slider"
    ></vue-slider>
  </div>
</template>

<script>
    import VueSlider from 'vue-slider-component';

    export default {
      name: 'NewSlider',
      props: ['initialValue', 'currentPercentage'],
      components: {
        VueSlider
      },
      data() {
          return {
              newPercentage: this.initialValue,
              lastPercentage: this.initialValue,
              marks: [0, 25, 50, 75, 100]
          }
      },
      methods: {
          update() {
              const newPercentage = parseInt(this.newPercentage);
              const lastPercentage = parseInt(this.lastPercentage);
              const currentPercentage = parseInt(this.currentPercentage);
              const nextPercentage = newPercentage + (currentPercentage - lastPercentage); // substract out the this sliders previous value than add in the new value

              if(nextPercentage > 100) {
                  this.newPercentage = (100 - currentPercentage) + lastPercentage; // get the difference between 100 and the total percentage then add / include the last percentage
                  this.$refs.slider.setValue(this.newPercentage);
              }

              this.$emit('input', parseInt(this.newPercentage));
              this.lastPercentage = this.newPercentage;
          }
      },
      computed: {
      }
    };
</script>