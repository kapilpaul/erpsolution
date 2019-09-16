<template>
    <div>
        <div class="remodal" data-remodal-id="modal">
            <button data-remodal-action="close" class="remodal-close"></button>
            <h1>Edit</h1>
            <br>
            <errors></errors>
            <form class="form-material">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="name" :value="name" @input="updateName" @keyup.enter="update">
                    </div>
                </div>
                <br>
                <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
                <button class="remodal-confirm" @click.prevent="update">Update</button>
            </form>
        </div>
    </div>
</template>

<script>
    import errors from '../../errors.vue';
    export default{
        components : {
            errors
        },
        computed: {
            name () {
                return this.$store.getters.categoryEditData.name
            },
            id() {
                return this.$store.getters.categoryEditData.id
            }
        },
        methods: {
            updateName(e) {
                this.$store.dispatch('setCategoryEditData', { 'id': this.id, 'name' : e.target.value });
            },
            update () {
                this.$store.dispatch('setValidationErrors', '');

                var data = {
                    name : this.name
                };
                var id = this.$store.getters.categoryEditData.id;


                this.$axios.patch('category/' + id, data,this.$auth.getHeader()).then(response => {
                    this.$store.dispatch('setCategories', '');
                    let inst = $('[data-remodal-id=modal]');
                    $('button.remodal-close', inst).click();

                    this.$swal({
                        title: 'Success!',
                        text: response.data.success,
                        type: 'success',
                        confirmButtonText: 'Cool'
                    });
                }).catch(error => {
                    this.$store.dispatch('setValidationErrors', error.response.data.errors);
                });
            }
        }
    }
</script>

<style>

</style>