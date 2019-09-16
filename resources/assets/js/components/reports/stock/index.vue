<template>
    <div>
        <stock-default :setLocation="'setStocks'"></stock-default>
    </div>
</template>

<script>
    import stockDefault from './default.vue'

    export default{
        components : {
            stockDefault
        }
    }
</script>

<style>

</style>