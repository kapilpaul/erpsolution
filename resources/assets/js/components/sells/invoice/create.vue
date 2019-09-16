<template>
    <div>
        <div class="card-body">
            <errors></errors>
            <form class="form-material" @submit.prevent="add">
                <customer-data @customer="getCustomer"></customer-data>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text"
                                       class="date-time-picker form-control"
                                       data-options='{"timepicker":false, "format":"d-m-Y"}'
                                       id="date"
                                />
                                <label class="form-label">Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="details" v-model="invoice.details" id="details"
                                       class="form-control">
                                <label class="form-label">Details</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p>Products</p>
                    </div>
                </div>

                <transition-group name="custom-classes-transition"
                    enter-active-class="animated slideInDown"
                    leave-active-class="animated slideOutDown"
                    :duration="{ leave: 200 }"
                    >

                    <div class="row" v-for="(item, index) in invoice.products" :key="index">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p class="p-label mb-0">Product</p>

                                    <v-select label="name" :filterable="false" :options="productItems" @search="onSearch" v-model="invoice['products'][index].product_id">
                                        <template slot="no-options">
                                            Type to search Products..
                                        </template>

                                        <template slot="option" slot-scope="option">
                                            <div class="d-center" v-if="option.photo != null">
                                                <img class="img-responsive" :src='productImageUrl + option.photo.photo'/>
                                                {{ option.name }} - {{ option.model }}
                                            </div>

                                            <div class="d-center" v-else>
                                                <img class="img-responsive" :src='productImageUrl + "s2.png"'/>
                                                {{ option.name }} - {{ option.model }}
                                            </div>
                                        </template>
                                        <template slot="selected-option" slot-scope="option">
                                            <div class="selected d-center" v-if="option.photo != null">
                                                <img class="img-responsive" :src='productImageUrl + option.photo.photo'/>
                                                {{ option.name }} - {{ option.model }}
                                            </div>

                                            <div class="selected d-center" v-else>
                                                <img class="img-responsive" :src='productImageUrl + "s2.png"'/>
                                                {{ option.name }} - {{ option.model }}
                                            </div>
                                        </template>
                                    </v-select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p class="p-label mb-0">In Stock</p>
                                    <input type="number" :value="invoice['products'][index].product_id.stock" class="form-control" name="stock" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p class="p-label mb-0">Quantity</p>
                                    <input type="number" class="form-control" name="quantity" v-model="item.quantity" step="01">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p class="p-label mb-0">Price</p>
                                    <input type="text" class="form-control" name="price" v-model="item.price">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p class="p-label mb-0">Discount</p>
                                    <input type="number" class="form-control" name="quantity" v-model="item.discount" step="1">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p class="p-label mb-0">Total</p>
                                    <input type="text" class="form-control" name="total" v-model="item.total = item.price">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group form-float">
                                <p class="p-label mb-0">&nbsp;</p>
                                <button @click.prevent="removeProduct(index)" type="button"
                                        class="btn btn-sm btn-danger">
                                    <i class="icon-minus"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </transition-group>

                <div class="row">
                    <div class="col-md-4">
                        <button @click="addnewProduct" type="button" class="btn btn-sm btn-primary">
                            <i class="icon-plus"></i>
                        </button>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <p class="p-label mb-0 float-right">Total Discount</p>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="grandtotal" v-model="discount"
                                       disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <p class="p-label mb-0 float-right">Grand Total</p>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="grandtotal" v-model="grandTotal"
                                       disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <p class="p-label mb-0 float-right">Paid Amount</p>
                    </div>
                    <div class="col-md-2">
                        <input-text
                                name="paid"
                                :showLabel=false
                                :value="invoice.paid_amount"
                                v-model="invoice.paid_amount"></input-text>
                    </div>

                    <div class="col-md-1"></div>
                </div>

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <p class="p-label mb-0 float-right">Due</p>
                    </div>
                    <div class="col-md-2">
                        <input-text
                                name="due"
                                :showLabel=false
                                :value="grandTotal - invoice.paid_amount" :disabled=true></input-text>
                    </div>

                    <div class="col-md-1"></div>
                </div>

                <button class="btn btn-warning waves-effect float-left mr-10"
                        @click.prevent="invoice.paid_amount = grandTotal">Full Paid</button>

                <submit-button v-if="invoice['products'].length > 0"></submit-button>
            </form>
        </div>
    </div>
</template>

<script>
    import errors from '../../errors.vue';
    import inputText from '../../common/form/input-text.vue';
    import vSelect from 'vue-select';
    import customerData from './customer.vue';
    import submitButton from '../../common/submit.vue';

    export default{
        data () {
            return {
                invoice : {
                    customer : {},
                    date: '',
                    details: '',
                    paid_amount : 0,
                    products : [
                        {
                            product_id : {
                                sale_price : 0,
                            },
                            quantity : 0,
                            price : 0,
                            discount : 0,
                            total : 0
                        }
                    ]
                },
                productItems: [],
                productImageUrl : process.env.MIX_APP_URL + '/assets/img/products/'
            }
        },
        components : {
            errors,
            vSelect,
            customerData,
            submitButton,
            inputText
        },
        computed : {
            grandTotal () {
                var grandtotal = 0;

                this.invoice['products'].forEach(item => {
                    grandtotal += item.total;
                });

                return Math.round(grandtotal - this.discount);
            },
            discount() {
                var discount = 0;

                this.invoice['products'].forEach(item => {
                    discount += item.discount;
                });

                return parseInt(discount);
            }
        },
        methods: {
            add () {
                this.$store.dispatch('setSubmitted', true);
                this.$store.dispatch('setValidationErrors', '');

                //setting jquery plugins data
                this.invoice.date = document.getElementById('date').value;

                //new variable to store invoicedata
                var invoiceData = {
                    customer : this.invoice.customer,
                    date: this.invoice.date,
                    details: this.invoice.details,
                    total_amount : 0,
                    total_tax : 0,
                    total_discount : this.discount,
                    grand_total : this.grandTotal,
                    paid_amount : this.invoice.paid_amount,
                    products : []
                };

                //iterate item and set product id
                this.invoice['products'].forEach(item => {
                    if(typeof item.product_id.id != 'undefined') {
                        invoiceData['products'].push(
                            {
                                product_id: item.product_id.id,
                                quantity: parseFloat(item.quantity),
                                price: item.price,
                                discount: item.price * (item.discount / 100),
                                total: item.total
                            }
                        );
                    }

                    invoiceData.total_amount += item.total;
                });

                //sending request
                this.$axios.post('invoice', invoiceData, this.$auth.getHeader()).then(response => {
                    this.invoice = {
                        customer : invoiceData.customer,
                        date: '',
                        details: '',
                        products : [
                            {
                                product_id : {
                                    sale_price : 0,
                                },
                                quantity : 0,
                                price : 0,
                                discount : 0,
                                total : 0
                            }
                        ]
                    };
                    this.productItems = [];
                    this.$store.dispatch('setSubmitted', false);

                    this.$swal({
                        title: 'Success!',
                        text: response.data.success,
                        type: 'success',
                        confirmButtonText: 'Cool'
                    }).then((result) => {
                        if (result.value) {
                            //window.location = process.env.MIX_APP_URL + 'invoice/create';
                        }
                    });
                }).catch(error => {
                    this.$store.dispatch('setSubmitted', false);
                    console.log(error.response.data);
                    this.$store.dispatch('setValidationErrors', error.response.data.errors);
                });
            },
            addnewProduct () {
                this.invoice['products'].push(
                    {
                        product_id : {
                            sale_price : 0,
                        },
                        quantity : 0,
                        price : 0,
                        discount : 0,
                        total : 0
                    }
                );
            },
            removeProduct(index) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.invoice['products'].splice(index, 1);
                    }
                })
            },
            onSearch(search, loading) {
                loading(true);
                this.searchProduct(loading, search, this);
            },
            searchProduct(loading, search) {
                if(search != '') {
                    this.$axios.get('products/search/' + search, this.$auth.getHeader()).then(response => {
                        this.productItems = response.data.products.data;
                        loading(false);
                    }).catch(error => {
                        this.$store.dispatch('setValidationErrors', error.response.data.errors);
                    });
                }
            },
            getCustomer(payload) {
                this.invoice.customer = payload;
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
    .v-select .dropdown-toggle {
        border: 0px !important;
    }
</style>

<style scoped>
    img {
        height: auto;
        max-width: 2.5rem;
        margin-right: 1rem;
    }

    .d-center {
        display: flex;
        align-items: center;
    }

    .selected img {
        width: auto;
        max-height: 23px;
        margin-right: 0.5rem;
    }

    .v-select .dropdown li {
        border-bottom: 1px solid rgba(112, 128, 144, 0.1);
    }

    .v-select .dropdown li:last-child {
        border-bottom: none;
    }

    .v-select .dropdown li a {
        padding: 10px 20px;
        width: 100%;
        font-size: 1.25em;
        color: #3c3c3c;
    }

    .v-select .dropdown-menu .active > a {
        color: #fff;
    }

    .mr-10 {
        margin-right: 10px !important;
    }
</style>