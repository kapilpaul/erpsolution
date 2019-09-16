<template>
    <div class="form-group form-float">
        <div class="form-line">
            <p class="p-label mb-0">{{ label | capitalize }}</p>
            <select :name="name" :id="name" class='form-control' @change="sendValue" v-model="selectedValue" @input="$emit('input', $event.target.value)">
                <option v-for="(item, index) in options" :value="item.value">{{ item.label }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: "inputSelect",
        props : ['name', 'label', 'options'],
        data () {
            return {
                selectedValue : ''
            }
        },
        methods : {
            sendValue() {
                this.$emit('selectedValue', this.selectedValue);
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