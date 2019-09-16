<template>

</template>

<script>
    export default {
        props : ['bank', 'balance'],
        mounted() {
            this.bank = JSON.parse(this.bank);
        }
    }
</script>

<style>

</style>