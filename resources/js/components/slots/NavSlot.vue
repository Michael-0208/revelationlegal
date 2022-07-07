<template>
    <div class="row mt-4">
        <div class="col-6 text-right">
            <router-link :to="last" class="btn bg-white rl-fg-blue h3 rounded"><slot name="last"></slot></router-link>
        </div>
        <div class="col-6 text-left">
            <router-link :to="next" class="btn h3 text-white btn-revelation-primary rounded"><slot name="next"></slot></router-link>
        </div>
    </div>
</template>
<script>
    export default {
        'name': 'NavSlot',
        props: ['last', 'next']
    }
</script>
