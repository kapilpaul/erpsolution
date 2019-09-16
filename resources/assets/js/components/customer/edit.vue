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
                        <p class="p-label mb-0 edit-p-label">Name *</p>
                        <input type="text" class="form-control" name="name" v-model="name"
                               @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Email</p>
                        <input type="text" class="form-control" name="email" v-model="email"
                               @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Mobile *</p>
                        <input type="text" class="form-control" name="mobile" v-model="mobile"
                               @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Address</p>
                        <textarea name="address" v-model="address" id="address" class="form-control" @keyup.enter="update"></textarea>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Previous Purchase Amount</p>
                        <input type="text" class="form-control" name="previous_purchase_amount" v-model="previous_purchase_amount" @keyup.enter="update">
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Balance</p>
                        <input type="text" class="form-control" name="balance" v-model="balance" @keyup.enter="update">
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
    import errors from '../errors.vue';
    export default{
        components : {
            errors
        },
        computed: {
            name : {
                get() {
                    return this.$store.getters.customerEditData.name
                },
                set(value) {
                    this.$store.dispatch('setCustomerEditData', this.updateValue('name', value));
                }
            },
            email : {
                get() {
                    return this.$store.getters.customerEditData.email
                },
                set(value) {
                    this.$store.dispatch('setCustomerEditData', this.updateValue('email', value));
                }
            },
            mobile: {
                get() {
                    return this.$store.getters.customerEditData.mobile
                },
                set(value) {
                    this.$store.dispatch('setCustomerEditData', this.updateValue('mobile', value));
                }
            },
            address: {
                get() {
                    return this.$store.getters.customerEditData.address
                },
                set(value) {
                    this.$store.dispatch('setCustomerEditData', this.updateValue('address', value));
                }
            },
            previous_purchase_amount: {
                get() {
                    return this.$store.getters.customerEditData.previous_purchase_amount
                },
                set(value) {
                    this.$store.dispatch('setCustomerEditData', this.updateValue('previous_purchase_amount', value));
                }
            },
            balance: {
                get() {
                    return this.$store.getters.customerEditData.balance
                },
                set(value) {
                    this.$store.dispatch('setCustomerEditData', this.updateValue('balance', value));
                }
            },
            id() {
                return this.$store.getters.customerEditData.id
            }
        },
        methods: {
            updateValue(elem, value) {
                var data = {
                    id: this.id,
                    name: this.name,
                    email: this.email,
                    mobile: this.mobile,
                    address: this.address,
                    previous_purchase_amount: this.previous_purchase_amount,
                    balance: this.balance
                };
                data[elem] = value;
                return data;
            },
            update () {
                this.$store.dispatch('setValidationErrors', '');

                var data = {
                    name: this.name,
                    email: this.email,
                    mobile: this.mobile,
                    address: this.address,
                    previous_purchase_amount: this.previous_purchase_amount,
                    balance: this.balance
                };
                var id = this.$store.getters.customerEditData.id;

                this.$axios.patch('customer/' + id, data,this.$auth.getHeader()).then(response => {
                    this.$store.dispatch('setCustomers', '');

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