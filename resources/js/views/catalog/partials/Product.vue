<template>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 mb-lg-4">
        <div class="product-list">
            <div class="product-list-top">
                <div class="product-list-image">
                    <router-link :to="'/'+product.slug" class="d-block text-center">
                        <img
                        class="img-fluid mx-auto"
                        v-lazy="{src: product.images[0].thumbnail, loading: '/img/product-loader.svg'}">
                    </router-link>
                </div>
                <div class="product-list-content">
                    <router-link :to="'/'+product.slug" class="text-center">
                        <h4 class="product-list-title">{{product.title}}</h4>
                        <h5 class="product-list-price">€ {{product.price}}</h5>
                    </router-link>
                </div>
            </div>
            <div class="product-list-extra">
                <div class="px-3 mb-3">
                    <wishlist-button :product="product"></wishlist-button>
                </div>
                <ul class="product-list-features list-group mb-0">
                    <li class="list-group-item" v-for="feature in product.key_features" :key="feature">
                        {{feature}}
                    </li>
                </ul>
            </div>
        </div>
    </div>

</template>
<script>
    import WishlistButton from './WishlistButton'

    export default {
        name: 'product-list',
        props: [
            'product'
        ],
        components: {
            "wishlist-button": WishlistButton
        }
    }
</script>
<style scoped lang="scss">
    @import '~@/_mixins';

    .product-list {
        position: relative;
        background: #fff;
        a {
            text-decoration: none;
        }
        .product-list-image {
            position: relative;
            img {
                padding: 1.5rem 0.5rem;
            }
        }
        .product-list-content {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .product-list-title {
            font-size: 0.85rem;
            color: $gray-600;
        }
        .product-list-price {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: $secondary;
        }
        .product-list-features {
            li {
                border-radius: 0;
                border-left: 0;
                border-right: 0;
                font-size: 0.65rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                &:last-child {
                    border-bottom: 0;
                }
            }
        }
        .product-list-top {
            border: 1px solid transparent;
            border-bottom: 0;
            padding-bottom: 1.25rem;
        }
        .product-list-extra {
            background: #fff;
            position: absolute;
            top: 99%;
            width: 100%;
            max-height: 0;
            overflow: auto;
            z-index: 2;
            border: 1px solid transparent;
            border-top: 0;
            transition: max-height 0.6s;
            box-shadow: 0 6px 12px -4px rgba(0, 0, 0, 0.1);
        }
        &:hover {
            .product-list-extra {
                max-height: 250px;

            }
            .product-list-top,
            .product-list-extra {
                border-color: $gray-200;
            }
        }
        @include media-breakpoint-up(lg) {
        }
    }
</style>
