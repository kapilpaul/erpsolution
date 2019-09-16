<template>
    <div class="form-group form-float">
        <div class="form-line">
            <textarea :name="name" :id="name" class='form-control' @input="$emit('input', $event.target.value)"></textarea>
            <label class="form-label">{{ label | capitalize }}</label>
        </div>
    </div>
</template>

<script>
    export default {
        name: "inputTextArea",
        props : ['name', 'placeholder', 'label'],
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