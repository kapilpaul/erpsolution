<template>
    <div class="form-group form-float">
        <div class="form-line">
            <p class="p-label mb-0" v-if="label && showLabel == true">{{ label | capitalize }}</p>
            <p class="p-label mb-0" v-else-if="showLabel == true">{{ name | capitalize }}</p>
            <input type="text" :name="name" :id="name" class='form-control' :placeholder="placeholder" :value="value" @input="$emit('input', $event.target.value)" :disabled="disabled">
        </div>
    </div>

</template>

<script>
    export default {
        name: "inputText",
        props: {
            name: String,
            placeholder: String,
            label: String,
            value: [String, Number],
            showLabel: {
                type : Boolean,
                default: true
            },
            disabled: {
                type : Boolean,
                default: false
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