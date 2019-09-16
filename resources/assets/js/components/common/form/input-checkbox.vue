<template>
    <div class="form-group">
        <label class="control-label mb-10 text-left">{{ name | capitalize }}</label>

        <div class="checkbox checkbox-success" v-for="(item, index) in options">
            <input type="checkbox" :id="name + index" @input="$emit('input', index)">
            <label :for="name">
                {{ item }}
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        name: "inputCheckbox",
        props : ['name', 'options'],
        data() {
            return {
                value : ''
            }
        },
        filters: {
            capitalize (value) {
                if (!value) return ''
                value = value.toString();
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        }
    }
</script>

<style scoped>

</style>