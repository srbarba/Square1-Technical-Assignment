<template>
    <app-layout>
        <div class="container">
            <div class="card-body bg-white mb-3">
                <h1 class="h5 mb-0">{{result.title}}</h1>
            </div>
            <div v-if="result.products_count <= 0" class="card-body bg-white mb-3 mb-lg-5">
                {{result.message}}
            </div>
            <product-list :result="result" :getResults="getCategory"></product-list>
        </div>
    </app-layout>
</template>
<script>
    import { mapMutations } from 'vuex'
    import { mapGetters } from 'vuex'
    import ProductList from './partials/ProductList'

    export default {
        name: 'Category',
        metaInfo: {
            title: 'Category Title',
            meta: [
                { name: 'description', content: 'Category description' }
            ]
        },
        components: {
            "product-list": ProductList
        },
        data () {
            return {
                result: {}
            }
        },
        mounted() {
            if( window.innerWidth > 992 ) this.setNavState(true)

            this.getCategory()
        },
        metaInfo () {
            return {
                title: this.result.title
            }
        },
        watch: { '$route' () { this.getCategory() } },
        computed: {
            orderBy: {
                get () {
                    return this.$store.getters.getOrderBy
                },
                set (value) {
                    this.$store.commit('setOrderBy', value)
                }
            }
        },
        methods: {
            ...mapMutations([
                'setNavState'
            ]),
            getCategory(page = 1) {
                if( this.$route.params.slug ){
                    axios.get('catalog/categories/'+this.$route.params.slug+'?page='+page+'&order='+this.$store.getters.getOrderBy)
                    .then(response => {
                        if( response.data.status == "success" && response.data.data.products_count > 0){
                            this.result = response.data.data

                            this.$store.commit('setBreadcrumbs', [
                                { name: 'Home', link: '/' },
                                { name: this.result.title, link: '/category/'+this.result.slug }
                            ])
                        } else {
                            this.result = {
                                products_count: 0,
                                title: "Products",
                                message: "There is not available products"
                            }
                        }
                    })
                } else {
                    axios.get('catalog/products?page='+page+'&order='+this.$store.getters.getOrderBy)
                    .then(response => {
                        if( response.data.status == "success" && response.data.data.total > 0){
                            this.result = {
                                title: "Products",
                                products_count: response.data.data.total
                            };
                            this.result.products = response.data.data;

                            this.$store.commit('setBreadcrumbs', [
                                { name: 'Home', link: '/' }
                            ])
                        } else {
                            this.result = {
                                products_count: 0,
                                title: "Products",
                                message: "There is not available products"
                            }
                        }
                    })
                }
            }
        }
    }
</script>
