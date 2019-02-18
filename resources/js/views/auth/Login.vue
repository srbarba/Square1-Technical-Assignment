<template>
    <app-layout>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <div class="card-body bg-white mb-3">
                        <h1 class="h5 mb-0">Login</h1>
                    </div>
                    <div class="alert alert-danger" v-if="error">
                        <p>There was an error, unable to sign in with those credentials.</p>
                    </div>
                    <div class="mb-3 mb-lg-5">
                        <div class="card-body bg-white">
                            <form autocomplete="off" @submit.prevent="login" method="post">
                                <div class="form-group">
                                    <label for="email">E-mail </label>
                                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password </label>
                                    <input type="password" id="password" class="form-control" v-model="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                        <div class="py-3 text-center">
                            <small>If you don't have an account, <router-link to="/register">click here</router-link></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>

    export default {
        mounted() {
            this.$store.commit('setNavState', false)
        },
        data(){
            return {
                email: null,
                password: null,
                error: false
            }
        },
        methods: {
            login(){
                var app = this
                this.$auth.login({
                    params: {
                        email: app.email,
                        password: app.password
                    },
                    success: function () {},
                    error: function () {
                        this.$swal('Incorrect user/password.');
                    },
                    rememberMe: true,
                    redirect: '/profile',
                    fetchUser: true,
                });
            },
        }
    }
</script>
