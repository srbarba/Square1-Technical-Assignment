import Router from 'vue-router'

// Import views
import PageNotFound from './views/PageNotFound'
import Register from './views/auth/Register'
import Login from './views/auth/Login'
import Profile from './views/auth/Profile'
import Wishlist from './views/auth/Wishlist'
import Category from './views/catalog/Category'
import Product from './views/catalog/Product'


export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Category,
            meta: {
                breadcrumb: [
                    { name: 'Home', link: '/' }
                ]
            }
        },
        {
            path: '/404',
            component: PageNotFound,
            meta: {
                breadcrumb: [
                    { name: 'Home', link: '/' },
                    { name: '404', link: '/' }
                ]
            }
        },
        {
            path: '/register',
            name: 'Register',
            component: Register,
            meta: {
                auth: false,
                breadcrumb: [
                    { name: 'Home', link: '/' },
                    { name: 'Register', link: '/register' }
                ]
            }
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: {
                auth: false,
                breadcrumb: [
                    { name: 'Home', link: '/' },
                    { name: 'Login', link: '/login' }
                ]
            }
        },
        {
            path: '/profile',
            name: 'Profile',
            component: Profile,
            meta: {
                auth: true,
                breadcrumb: [
                    { name: 'Home', link: '/' },
                    { name: 'Profile', link: '/profile' }
                ]
            }
        },
        {
            path: '/wishlist/:id?',
            name: 'Wishlist',
            component: Wishlist,
            meta: {
                breadcrumb: [
                    { name: 'Home', link: '/' },
                    { name: 'Wishlist', link: '/profile' }
                ]
            }
        },
        {
            path: '/category/:slug',
            name: 'Category',
            component: Category,
            meta: {
                breadcrumb: [
                    { name: 'Home', link: '/' }
                ]
            }
        },
        {
            path: '/:slug',
            component: Product,
            meta: {
                breadcrumb: [
                    { name: 'Home', link: '/' }
                ]
            }
        }
    ],
});
