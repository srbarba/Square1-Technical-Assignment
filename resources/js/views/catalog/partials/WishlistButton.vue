<template>
    <div>
        <a v-if="!$auth.check()" href="#" @click.prevent="alert()" class="btn btn-outline-terciary btn-block rounded-0">Add to wishlist <i class="far fa-star"></i></a>
        <a v-if="$auth.check() && getWhislist.indexOf(product.product_id) < 0" href="#" @click.prevent="addToWishlist(product.product_id)" class="btn btn-outline-terciary btn-block rounded-0">Add to wishlist <i class="far fa-star"></i></a>
        <a v-if="$auth.check() && getWhislist.indexOf(product.product_id) >= 0" href="#" @click.prevent="removeFromWishlist(product.product_id)" class="btn btn-outline-terciary btn-block rounded-0">Remove from wishlist <i class="fa fa-star"></i></a>
    </div>
</template>
<script>
    import { mapMutations } from 'vuex'
    import { mapGetters } from 'vuex'

    export default {
        name: 'gallery',
        props: [
            'product'
        ],
        computed: {
            ...mapGetters([
                'getWhislist'
            ])
        },
        methods: {
            ...mapMutations([
                'setWishlist'
            ]),
            alert() {
                this.$swal('You have to be logged in to add products to your wishlist!', {
                    buttons: {
                        login: {
                            text: "Login",
                            value: "login"
                        },
                        register: {
                            text: "Register",
                            value: "register"
                        },
                        defeat: "Maybe later",
                    }
                }).then((value) => {
                    switch (value) {
                        case "login":
                            this.$router.push('/login')
                        break;

                        case "register":
                            this.$router.push('/register')
                        break;
                    }
                });
            },
            addToWishlist(product_id) {
                axios.get('profile/wishlist/add/'+this.$auth.user().id+'/'+product_id)
                .then(response => {
                    if( response.data.status == "success" ){
                        let wishlist = this.$store.getters.getWhislist

                        this.$store.commit('setWishlist', [...wishlist, product_id])
                    }
                })
            },
            removeFromWishlist(product_id) {
                axios.get('profile/wishlist/remove/'+this.$auth.user().id+'/'+product_id)
                .then(response => {
                    if( response.data.status == "success" ){
                        let wishlist = this.$store.getters.getWhislist

                        wishlist.splice(wishlist.indexOf(product_id), 1)
                        this.$store.commit('setWishlist', wishlist)
                    }
                })
            }
        }
    }
</script>
