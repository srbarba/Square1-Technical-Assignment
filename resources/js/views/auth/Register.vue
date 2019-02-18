<template>
    <app-layout>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <div class="card-body bg-white mb-3">
                        <h1 class="h5 mb-0">Register</h1>
                    </div>
                    <div class="alert alert-danger" v-if="error && !success">
                        <p>There was an error, unable to complete registration.</p>
                    </div>
                    <div class="alert alert-success" v-if="success">
                        <p>Registration completed. You can now <router-link :to="{name:'Login'}">sign in.</router-link></p>
                    </div>
                    <div class="mb-3 mb-lg-5" v-if="!success">
                        <div class="card-body bg-white">
                            <form autocomplete="off" @submit.prevent="register" method="post">
                                <div class="form-group" v-bind:class="{ 'has-error': error && errors.name }">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control" v-model="name" required>
                                    <span class="help-block" v-if="error && errors.name">{{ errors.name }}</span>
                                </div>
                                <div class="form-group" v-bind:class="{ 'has-error': error && errors.email }">
                                    <label for="email">E-mail</label>
                                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                                    <span class="help-block" v-if="error && errors.email">{{ errors.email }}</span>
                                </div>
                                <div class="form-group" v-bind:class="{ 'has-error': error && errors.password }">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" class="form-control" v-model="password" required>
                                    <span class="help-block" v-if="error && errors.password">{{ errors.password }}</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                        <div class="py-3 text-center">
                            <small>If you already have an account, <router-link to="/login">click here</router-link></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    export default {
        data(){
            return {
                name: '',
                email: '',
                password: '',
                error: false,
                errors: {},
                success: false
            };
        },
        methods: {
            register(){
                var app = this
                this.$auth.register({
                    data: {
                        name: app.name,
                        email: app.email,
                        password: app.password
                    },
                    success: function () {
                        app.success = true
                    },
                    error: function (resp) {
                        app.error = true;
                        app.errors = resp.response.data.errors;
                    },
                    redirect: null
                })
            }
        }
    }
</script>
