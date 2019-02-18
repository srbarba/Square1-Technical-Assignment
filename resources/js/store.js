import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        navState: false,
        breadcrumbs: [],
        orderBy: "price:ASC",
        wishlist: []
    },
    getters: {
        navState: state => state.navState,
        getBreadcrumbs: state => state.breadcrumbs,
        getOrderBy: state => state.orderBy,
        getWhislist: state => state.wishlist
    },
    mutations: {
        toggleNavState: state => state.navState = !state.navState,
        setNavState: (state, newNavState) => state.navState = newNavState,
        setBreadcrumbs: (state, newState) => state.breadcrumbs = newState,
        setOrderBy: (state, newState) => state.orderBy = newState,
        setWishlist: (state, newState) => state.wishlist = newState
    }
});
