<template>
    <div>
        <div class="card-body">
            <errors></errors>
            <form class="form-material" @submit.prevent="add">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="name" v-model="name">
                        <label class="form-label">Name</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="mobile" v-model="mobile">
                        <label class="form-label">Mobile</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="address" v-model="address">
                        <label class="form-label">Address</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <!--<input type="text" class="form-control" name="name" v-model="details">-->
                        <textarea name="details" v-model="details" id="details" class="form-control"></textarea>
                        <label class="form-label">Details</label>
                    </div>
                </div>

                <submit-button></submit-button>
            </form>
        </div>
    </div>
</template>

<script>
    import errors from '../../errors.vue';
    import submitButton from '../../common/submit.vue';

    export default{
        data () {
            return {
                name: '',
                mobile: '',
                address: '',
                details: ''
            }
        },
        components : {
            errors,
            submitButton
        },
        methods: {
            add () {
                this.$store.dispatch('setSubmitted', true);
                this.$store.dispatch('setValidationErrors', '');

                var data = {
                    name : this.name,
                    mobile : this.mobile,
                    address : this.address,
                    details : this.details
                };

                this.$axios.post('suppliers', data,this.$auth.getHeader()).then(response => {
                    this.$store.dispatch('setSubmitted', false);
                    this.name = this.mobile = this.address = this.details = '';
                    this.$swal({
                        title: 'Success!',
                        text: response.data.success,
                        type: 'success',
                        confirmButtonText: 'Cool'
                    });
                }).catch(error => {
                    this.$store.dispatch('setSubmitted', false);
                    this.$store.dispatch('setValidationErrors', error.response.data.errors);
                });
            }
        }
    }
</script>

<style>

</style>