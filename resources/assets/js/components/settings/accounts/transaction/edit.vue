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
                        <p class="p-label mb-0 edit-p-label">Date *</p>
                        <input type="text"
                           class="date-time-picker form-control"
                           data-options='{"timepicker":false, "format":"d-m-Y"}'
                           id="date"
                           :value="date"
                        />
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Account Type *</p>
                        <select class="form-control" id="account_type" @keyup.enter="update">
                            <option value="debit" :selected="debit != 0">Debit (+)</option>
                            <option value="credit" :selected="credit != 0">Credit (-)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Withdraw / Deposite ID *</p>
                        <input type="text" class="form-control" name="slip_id" v-model="slip_id" @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Amount *</p>
                        <input v-if="debit != 0" type="number" class="form-control" name="amount" @keyup.enter="update" :value="debit" id="amount">
                        <input v-else-if="credit != 0" type="number" class="form-control" name="amount"  id="amount" @keyup.enter="update"  :value="credit" >
                    </div>
                </div>
                
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Description</p>
                        <textarea name="description" v-model="description" id="description" class="form-control" @keyup.enter="update"></textarea>
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
    import errors from '../../../errors.vue';
    export default{
        props : ['code'],
        components : {
            errors
        },
        computed: {
            date : {
                get() {
                    return this.$store.getters.bankTransactionEditData.date
                },
                set(value) {
                    this.$store.dispatch('setBankTransactionEditData', this.updateValue('name', value));
                }
            },
            debit : {
                get() {
                    return this.$store.getters.bankTransactionEditData.debit
                },
                set(value) {
                    this.$store.dispatch('setBankTransactionEditData', this.updateValue('debit', value));
                }
            },
            credit: {
                get() {
                    return this.$store.getters.bankTransactionEditData.credit
                },
                set(value) {
                    this.$store.dispatch('setBankTransactionEditData', this.updateValue('credit', value));
                }
            },
            slip_id: {
                get() {
                    return this.$store.getters.bankTransactionEditData.slip_id
                },
                set(value) {
                    this.$store.dispatch('setBankTransactionEditData', this.updateValue('slip_id', value));
                }
            },
            description: {
                get() {
                    return this.$store.getters.bankTransactionEditData.description
                },
                set(value) {
                    this.$store.dispatch('setBankTransactionEditData', this.updateValue('description', value));
                }
            },
            id() {
                return this.$store.getters.bankTransactionEditData.id
            }
        },
        methods: {
            updateValue(elem, value) {
                var data = {
                    id: this.id,
                    date: this.date,
                    slip_id: this.slip_id,
                    description: this.description
                };
                data[elem] = value;
                return data;
            },
            update () {
                this.$store.dispatch('setValidationErrors', '');
                
                //setting jquery plugins data
                let date = document.getElementById('date').value;
                let amount = document.getElementById('amount').value;
                let account_type = document.getElementById('account_type').value;

                var data = {
                    date: date,
                    slip_id: this.slip_id,
                    description: this.description,
                    amount: amount,
                    account_type : account_type
                };
                var id = this.$store.getters.bankTransactionEditData.id;

                this.$axios.patch('banks/transaction/' + id, data,this.$auth.getHeader()).then(response => {
                    if(this.code) {
                        this.$store.dispatch('setBankTransactions', {code : this.code});
                    }
                    

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
    .edit-p-label{
        text-align: left;
        font-size: 12px;
    }
</style>