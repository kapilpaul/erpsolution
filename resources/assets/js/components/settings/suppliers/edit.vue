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
                        <input type="text" class="form-control" name="name" v-model="name"
                               @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="mobile" v-model="mobile"
                               @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="address" v-model="address"
                               @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="details" class="form-control" id="details" v-model="details" @keyup.enter="update">{{
                            details }}</textarea>
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
            name : {
                get() {
                    return this.$store.getters.suppliersEditData.name
                },
                set(value) {
                    this.$store.dispatch('setSuppliersEditData', this.updateValue('name', value));
                }
            },
            mobile: {
                get() {
                    return this.$store.getters.suppliersEditData.mobile
                },
                set(value) {
                    this.$store.dispatch('setSuppliersEditData', this.updateValue('mobile', value));
                }
            },
            address: {
                get() {
                    return this.$store.getters.suppliersEditData.address
                },
                set(value) {
                    this.$store.dispatch('setSuppliersEditData', this.updateValue('address', value));
                }
            },
            details: {
                get() {
                    return this.$store.getters.suppliersEditData.details
                },
                set(value) {
                    this.$store.dispatch('setSuppliersEditData', this.updateValue('details', value));
                }
            },
            id() {
                return this.$store.getters.suppliersEditData.id
            }
        },
        methods: {
            updateValue(elem, value) {
                var data = {
                    id: this.id,
                    name: this.name,
                    mobile: this.mobile,
                    address: this.address,
                    details: this.details
                };
                data[elem] = value;
                console.log(data);
                return data;
            },
            update () {
                this.$store.dispatch('setValidationErrors', '');
                var data = {
                    name: this.name,
                    mobile: this.mobile,
                    address: this.address,
                    details: this.details
                };
                var id = this.$store.getters.suppliersEditData.id;

                this.$axios.patch('suppliers/' + id, data,this.$auth.getHeader()).then(response => {
                    this.$store.dispatch('setSuppliers', '');
                    let inst = $('[data-remodal-id=modal]');
                    $('button.remodal-close', inst).click();
                    this.$swal({
                        title: 'Success!',
                        text: response.data.success,
                        type: 'success',
                        confirmButtonText: 'Cool'
                    });
                }).catch(error => {
                    console.log(error.response);
                    this.$store.dispatch('setValidationErrors', error.response.data.errors);
                });
            }
        }
    }
</script>

<style>

</style>