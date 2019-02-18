<template>
    <div id="app-container" class="h-100 d-flex flex-column" v-bind:class="{ navOpened: navState }">
        <app-header v-on:toggle-nav="toggleNavState()"></app-header>
        <app-nav v-on:toggle-nav="toggleNavState()"></app-nav>
        <main ref="main-content">
            <app-breadcrumbs ref="breadcrumbs"></app-breadcrumbs>
            <app-main-content><slot></slot></app-main-content>
        </main>
        <app-footer></app-footer>
    </div>
</template>
<style scoped lang="scss">
    @import '~@/_mixins';

    #app-container {
        margin-left: 0;
        transition: margin-left 0.4s;
        @include media-breakpoint-up(md) {
            &.navOpened {
                margin-left: $nav-width;
            }
        }
    }
    main {
        margin-top: $header-height;
        @include media-breakpoint-up(lg) {
            margin-top: $header-height-lg;
        }
    }
</style>
<script>
    import { mapMutations } from 'vuex'
    import { mapGetters } from 'vuex'

    import Header from './partials/Header'
    import Nav from './partials/Nav'
    import Breadcrumbs from './partials/Breadcrumbs'
    import MainContent from './partials/MainContent'
    import Footer from './partials/Footer'

    export default {
        name: 'OneColumnFluid',
        components: {
            'app-header': Header,
            'app-nav': Nav,
            'app-main-content': MainContent,
            'app-footer': Footer,
            'app-breadcrumbs': Breadcrumbs
        },
        mounted() {
            this.setBreadcrumbs(this.$route.meta.breadcrumb)
            this.getUserWishlist()
        },
        computed: {
            ...mapGetters([
                'navState',
                'getWhislist'
            ])
        },
        methods: {
            ...mapMutations([
                'toggleNavState',
                'setNavState',
                'setBreadcrumbs'
            ]),
            getUserWishlist() {
                if( this.$auth.check() ) {
                    axios.get('catalog/products/user/'+this.$auth.user().id)
                    .then(response => {
                        if( response.data.status == "success" ){
                            this.$store.commit('setWishlist', response.data.data)
                        }
                    })
                }
            }
        }
    }
</script>
