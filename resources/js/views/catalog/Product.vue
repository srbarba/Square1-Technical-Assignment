<template>
    <app-layout>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card-body bg-white mb-3">
                        <carousel :per-page="1">
                            <slide v-for="image in result.images" :key="image.image_id">
                                <img
                                class="img-fluid mx-auto"
                                v-lazy="{src: image.large, loading: '/img/product-loader.svg'}">
                            </slide>
                        </carousel>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card-body bg-white mb-3">
                        <div class="product-title mb-3 mb-lg-4">
                            <h1 class="h5 mb-0 d-inline-block">{{result.title}}</h1>
                            <div class="price">
                                € {{result.price}}
                                <del v-if="result.price_previous > 0 ">€ {{result.price_previous}}</del>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <wishlist-button :product="result"></wishlist-button>
                            </div>
                        </div>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item rounded-0" v-for="feature in result.key_features" :key="feature">
                                {{feature}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mb-3 mb-lg-5">
                <div class="card-body bg-white">
                    <nav>
                        <div class="nav nav-tabs" id="product-tabs" role="tablist">
                            <a class="nav-item nav-link active rounded-0" id="product-tabs-description" data-toggle="tab" href="#nav-description" role="tab" aria-controls="nav-description" aria-selected="true">Description</a>
                            <a v-if="result.videos > 0" class="nav-item nav-link rounded-0" id="product-tabs-videos" data-toggle="tab" href="#nav-videos" role="tab" aria-controls="nav-videos" aria-selected="false">Videos</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active py-4" id="nav-description" role="tabpanel" aria-labelledby="product-tabs-description">
                            <div v-html="result.description"></div>
                        </div>
                        <div v-if="result.videos > 0" class="tab-pane fade py-4" id="nav-videos" role="tabpanel" aria-labelledby="product-tabs-videos">
                            <div v-for="video in result.videos" :key="video.video_id" class="col-lg-6">
                                <iframe :src="video.url" width="100%" height="450px"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped lang="scss">
    @import '~@/_mixins';

    h1 {
        font-size: 1.5rem;
        line-height: 2.5rem;
    }
    .price {
        font-size: 3rem;
        font-weight: 700;
        color: $primary;
        del {
            font-size: 1rem;
            font-weight: 400;
            color: $gray-300;
        }
    }
</style>

<script>
    import { Carousel, Slide } from 'vue-carousel';
    import WishlistButton from './partials/WishlistButton'

    export default {
        name: 'Product',
        metaInfo: {
            title: 'Products',
            titleTemplate: null,
        },
        components: {
            "wishlist-button": WishlistButton,
            Carousel,
            Slide
        },
        data () {
            return {
                result: {}
            }
        },
        mounted() {
            this.getProduct()
        },
        metaInfo () {
            return {
                title: this.result.meta_title,
                titleTemplate: null,
                meta: [
                    {name: 'description', content: this.result.meta_description}
                ]
            }
        },
        methods: {
            getProduct() {
                axios.get('catalog/products/'+this.$route.params.slug)
                .then(response => {
                    if( response.data.status == "success" ){
                        this.result = response.data.data;
                        this.description = String(response.data.data.description);
                        this.$store.commit('setBreadcrumbs', [
                            { name: 'Home', link: '/' },
                            { name: this.result.title, link: '/'+this.result.slug }
                        ])
                    } else {
                        this.$route.push('/404')
                    }
                })
            }
        }
    }
</script>
