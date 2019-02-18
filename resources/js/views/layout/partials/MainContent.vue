<template>
    <div class="main-content">
        <slot></slot>
    </div>
</template>
<style scoped lang="scss">
    @import '~@/_mixins';

    .main-content {
        padding-top: 2.125rem;
        @include media-breakpoint-up(lg) {
            padding-top: 1.875rem;
        }
    }
</style>
<script>
    export default {
        name: 'app-main-content',
        components: {
        }
    }
</script>
