<template>
    <footer id="main-footer" class="mt-auto py-3">
        <div class="container">
            <div class="text-center small">
                 @ {{year}} MyFavouriteAppliances.wow
            </div>
        </div>
    </footer>
</template>
<style scoped lang="scss">
    #main-footer {
        background: #e9e2dc;
    }
</style>
<script>
    export default {
        name: 'app-footer',
        data () {
            return {
                year: (new Date()).getFullYear()
            }
        }
    }
</script>
