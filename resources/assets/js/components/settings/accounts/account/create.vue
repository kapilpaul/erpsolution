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

                <submit-button></submit-button>
            </form>
        </div>
    </div>
</template>

<script>
    import errors from '../../../errors.vue';
    import submitButton from '../../../common/submit.vue';

    export default{
        data () {
            return {
                name: ''
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
                    name : this.name
                };

                this.$axios.post('accounts', data,this.$auth.getHeader()).then(response => {
                    this.name = '';
                    this.$store.dispatch('setSubmitted', false);

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