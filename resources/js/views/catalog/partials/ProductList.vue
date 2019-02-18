<template>
    <div v-if="result.products_count > 0" class="card-body bg-white mb-3 mb-lg-5">
        <div class="card-title">
            <div class="row justify-content-end">
                <div class="col-sm d-none d-sm-block">
                    <small>{{result.products_count}} products</small>
                </div>
                <div class="col-12 col-sm-4 col-lg-3">
                    <select v-model="orderBy" @change="getResults(result.products.current_page)" class="custom-select custom-select-sm">
                        <option value="price:ASC">Price up</option>
                        <option value="price:DESC">Price down</option>
                        <option value="title:ASC">Title up</option>
                        <option value="title:DESC">Title down</option>
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div class="product-list-result mb-3 mb-lg-5">
            <div class="row">
                <product v-for="product in result.products.data" :key="product.product_id" :product="product"></product>
            </div>
        </div>
        <div class="pagination">
            <app-pagination :data="result.products" :limit="3" @pagination-change-page="getResults"></app-pagination>
        </div>
    </div>
</template>
<script>
    import { mapMutations } from 'vuex'
    import { mapGetters } from 'vuex'
    import Product from './Product'

    export default {
        name: 'product-list',
        components: {
            "product": Product
        },
        props: [
            'result',
            'getResults'
        ],
        computed: {
            orderBy: {
                get () {
                    return this.$store.getters.getOrderBy
                },
                set (value) {
                    this.$store.commit('setOrderBy', value)
                }
            }
        }
    }
</script>
