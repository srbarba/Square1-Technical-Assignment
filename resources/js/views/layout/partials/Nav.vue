<template>
    <div id="app-nav-header">
        <header class="titular d-flex">
            Categories
            <a href="#" @click.prevent="$emit('toggle-nav')" class="text-white ml-auto"><i class="fa fa-times"></i></a>
        </header>
        <ul class="list-inline">
            <li>
                <router-link :to="{ name: 'Home' }" exact>All</router-link>
            </li>
            <li v-for="category in categories" :key="category.category_id">
                <router-link :to="{ path: '/category/'+category.slug }">
                    {{ category.title }}
                </router-link>
            </li>
        </ul>
    </div>
</template>
<style scoped lang="scss">
    @import '~@/_mixins';

    #app-nav-header {
        position: fixed;
        left: -($nav-width+0.5);
        width: $nav-width;
        top: $header-height;
        z-index: $nav-zindex;
        background: #fff;
        height: 100%;
        box-shadow: 0.15rem 0.35rem 0.5rem 0rem rgba(0, 0, 0, 0.25);
        transition: left 0.4s;
        padding-bottom: 6.25rem;
        overflow-y: auto;
        .navOpened & {
            left: 0;
        }

        .titular {
            background: $primary;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #fff;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            position: relative;
            &:after {
                background: $primary;
                bottom: -.38462rem;
                content: " ";
                display: inline-block;
                height: .76923rem;
                left: 1.30769rem;
                position: absolute;
                transform: rotate(45deg);
                width: .76923rem;
                z-index: 1;
            }
        }
        ul > li {
            > a {
                display: block;
                padding: 0.25rem 1rem;
                font-size: 0.85rem;
                color: $secondary;
                text-decoration: none;
                &:hover, &:active, &:focus {
                    background: #f0f0f0;
                }
                &.router-link-active {
                    background: #f0f0f0;
                }
            }
            > ul > li > a {
                padding: 0.25rem 1rem 0.25rem 2rem;
            }
        }
        @include media-breakpoint-up(lg) {
            width: $nav-width-lg;
            top: $header-height-lg;
        }
    }
</style>
<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'app-nav',
        data() {
            return {
                categories: {}
            }
        },
        mounted() {
            this.getCategories();
        },
        methods: {
            getCategories() {
                axios.get('catalog/categories')
                .then(response => {
                    if( response.data.status == "success" ){
                        this.categories = response.data.data;
                    }
                });
            }
        }
    }
</script>
