<template>
    <div id="app-top-bar">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li
                    v-for="(breadcrumb, idx) in getBreadcrumbs"
                    :key="idx"
                    :class="{'linked': !!breadcrumb.link}"
                    class="breadcrumb-item">
                    <router-link :to="{ path: breadcrumb.link }" exact>
                        {{ breadcrumb.name }}
                    </router-link>
                </li>
            </ol>
        </div>
    </div>
</template>
<style scoped lang="scss">
    @import '~@/_mixins';

    #app-top-bar {
        background: #e9e2dc;
        font-size: 0.75rem;
        p {
            margin-bottom: 0;
        }
        a {
            color: $secondary;
        }
    }
    .breadcrumb {
        padding: 0.5rem 0;
        border-radius: 0;
        background: transparent;
        margin-bottom: 0;
        color: $secondary;
    }
    .breadcrumb-item + .breadcrumb-item::before{
        color: $secondary;
    }
</style>
<script>
    import { mapMutations } from 'vuex'
    import { mapGetters } from 'vuex'

    export default {
        name: 'app-breadcrumbs',

        computed: {
            ...mapGetters([
                'getBreadcrumbs'
            ])
        }
    }
</script>
