<template>
    <app-layout>
        <div class="container">
            <div class="card-body bg-white mb-3">
                <h1 class="h5 mb-0">Wishlist</h1>
            </div>
            <div v-if="result.products_count <= 0" class="card-body bg-white mb-3 mb-lg-5">
                {{result.message}}
            </div>
            <product-list :result="result" :getResults="getProducts"></product-list>
        </div>
    </app-layout>
</template>
<script>
    import ProductList from '../catalog/partials/ProductList'

    export default {
        name: 'Wishlist',
        metaInfo: {
            title: 'Wishlist',
            meta: [
                { name: 'description', content: 'Profile Wishlist' }
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
            this.getProducts()

            this.$store.watch(
                (state, getters) => getters.getWhislist,
                (newValue, oldValue) => {
                    this.getProducts()
                }
            )
        },
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
            getProducts(page = 1) {
                axios.get('catalog/products/user/'+this.$hashids.decode(this.$route.params.id)+'?page='+page+'&order='+this.$store.getters.getOrderBy)
                .then(response => {
                    if( response.data.status == "success" && response.data.data.total > 0 ){
                        this.result = {
                            products_count: response.data.data.total
                        }
                        this.result.products = response.data.data;
                    } else {
                        this.result = {
                            products_count: 0,
                            message: "There is not available products"
                        }
                    }
                }).catch(error => {
                    this.$router.push('/')
                })
            }
        }
    }
</script>
