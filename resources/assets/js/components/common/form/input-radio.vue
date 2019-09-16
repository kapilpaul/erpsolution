<template>
    <div class="form-group">
        <p class="p-label mb-0">{{ label | capitalize }}</p>
        <div v-for="(item, index) in options" :class="{'float-left' : index < (options.length - 1)}">
            <input type="radio" :name="name" :id="item.value" class="" :value="item.value" @input="$emit('input', $event.target.value)" :checked="item.selected == true">
            <label :for="item.value">{{ item.label }}</label>
        </div>
    </div>

</template>

<script>
    export default {
        name: "inputRadio",
        props : ['name', 'label', 'options'],
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
    .form-group label{
        margin-right: 15px;
    }
</style>