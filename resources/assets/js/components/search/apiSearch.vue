<template>
    <div class="form-group focused">
        <input class="form-control r-0" v-model="search" @input="itemSearch" type="text" placeholder="Search...">
    </div>
</template>

<script>
    export default {
        props : ['setLocation'],
        data () {
            return {
                search : "",
            }
        },
        methods : {
            itemSearch() {
                this.$store.dispatch(this.setLocation, { search: this.search });
            }
        }
    }
</script>

<style>

</style>