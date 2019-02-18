<template>
    <header id="main-header">
        <div class="container-fluid px-1 px-md-3">
            <ul class="list-inline d-flex mb-0">
                <li class="list-inline-item align-self-center pr-lg-3">
                    <div class="logo">
                        <router-link :to="{ name: 'Home' }">
                            <img src="/img/logo.svg" class="img-fluid d-none d-lg-inline-block" alt="MyFavouriteAppliances.wow">
                            <img src="/img/logo-text.svg" class="img-fluid d-lg-none" alt="MyFavouriteAppliances.wow">
                        </router-link>
                    </div>
                </li>
                <li class="list-inline-item align-self-center d-none d-lg-inline-block">
                    <a href="#" @click.prevent="$emit('toggle-nav')" class="btn btn-outline-primary"><i class="fa fa-bars"></i></a>
                </li>

                <li class="list-inline-item align-self-center ml-auto">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item align-self-center d-lg-none">
                            <a href="#" @click.prevent="$emit('toggle-nav')" class="btn btn-outline-primary"><i class="fa fa-bars"></i></a>
                        </li>
                        <li class="list-inline-item align-self-center">
                            <router-link v-if="!$auth.check()" :to="{ name: 'Login' }" class="btn btn-outline-primary"><span class="d-none d-sm-inline-block pr-lg-2">My Wishlist</span><i class="fa fa-star"></i></router-link>
                            <router-link v-else :to="{ name: 'Profile' }" class="btn btn-outline-primary"><span class="d-none d-sm-inline-block pr-lg-2">My Wishlist</span><i class="fa fa-star"></i></router-link>
                        </li>
                        <li class="list-inline-item align-self-center">
                            <router-link v-if="!$auth.check()" :to="{ name: 'Login' }" class="btn btn-outline-secondary"><span class="d-none d-sm-inline-block pr-lg-2">My account</span><i class="fa fa-user"></i></router-link>
                            <a v-else href="#" class="btn btn-outline-secondary" @click.prevent="$auth.logout()"><span class="d-none d-sm-inline-block pr-lg-2">Logout</span><i class="fa fa-sign-out-alt"></i></a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </header>
</template>
<style scoped lang="scss">
    @import '~@/_mixins';

    #main-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9;
        background: #fff;
        height: $header-height;
        border-top: .38462rem solid $primary;
        box-shadow: 0 0.23077rem 0.38462rem 0 rgba(0,0,0,.25);
        @include media-breakpoint-up(lg) {
            height: $header-height-lg;
        }
    }
    #app-nav-header {
        width: 250px;
        background: #fff;
        height: 100%;
    }
    .logo img {
        margin: 0.45rem 0;
        max-height: 2.625rem;
        @include media-breakpoint-up(lg) {
            margin: 0.85rem 0;
            max-height: 3rem;
        }
    }
</style>
<script>
    export default {
        name: 'app-header'
    }
</script>
