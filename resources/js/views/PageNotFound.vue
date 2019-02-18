<template>
    <app-layout>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <div class="card-body bg-white mb-3">
                        <h1 class="h5 mb-0">PageNotFound</h1>
                    </div>
                    <div class="mb-3 mb-lg-5">
                        <div class="card-body bg-white">
                            <span>We can't find the content you are looking for.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    export default {
        name: 'PageNotFound',
            metaInfo: {
            title: '404 - Page not found',
            meta: [
                { name: 'description', content: 'We can\'t find what your are looking for.' }
            ]
        }
    }
</script>
