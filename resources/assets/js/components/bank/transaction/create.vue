<template>
    <div>
        <div class="card-body">
            <errors></errors>
            <form class="form-material" @submit.prevent="add">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text"
                                       class="date-time-picker form-control"
                                       data-options='{"timepicker":false, "format":"d-m-Y"}'
                                       id="date"
                                />
                        <label class="form-label">Date *</label>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0">Account Type *</p>
                        <select class="form-control custom-select select2" id="account_type">
                            <option value="debit">Debit (+)</option>
                            <option value="credit">Credit (-)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0">Bank Name *</p>
                        <select class="form-control custom-select select2" id="bank_id">
                            <option v-for="(item, index) in banks" :value="index">{{ item }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="slip_id" v-model="itemData.slip_id">
                        <label class="form-label">Withdraw / Deposite ID *</label>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="number" class="form-control" name="amount" v-model="itemData.amount">
                        <label class="form-label">Amount *</label>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="description" v-model="itemData.description" id="description" class="form-control"></textarea>
                        <label class="form-label">Description</label>
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
        props : ['banks'],
        data () {
            return {
                itemData : {
                    date: '',
                    account_type : '',
                    bank_id : '',
                    slip_id : '',
                    amount : '',
                    description : ''
                }
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
                
                //setting jquery plugins data
                this.itemData.date = document.getElementById('date').value;
                this.itemData.account_type = document.getElementById('account_type').value;
                this.itemData.bank_id = document.getElementById('bank_id').value;

                this.$axios.post('banks/transaction', this.itemData, this.$auth.getHeader()).then(response => {
                    this.$store.dispatch('setSubmitted', false);
                    document.getElementById('date').value = '';
                    this.itemData = {
                        date: '',
                        account_type : '',
                        bank_id : '',
                        slip_id : '',
                        amount : '',
                        description : ''
                    }
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
    .p-label{
        font-size:12px;
        font-weight: 400;
        color: #86939e;
    }
    .select2-container--default .select2-search--dropdown::before {
        content: "";
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        padding: 5px 10px 5px 10px;
    }
    .select2-container--default .select2-selection--single {
        border: 0px solid #e1e8ee;
    }
    .mb-10{
        margin-bottom: 10px !important;
    }
</style>