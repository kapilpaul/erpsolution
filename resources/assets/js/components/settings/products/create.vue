<template>
    <div>
        <div class="card-body">
            <errors></errors>
            <form class="form-material" @submit.prevent="add">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="name" v-model="product.name">
                        <label class="form-label">Name</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="model" v-model="product.model">
                        <label class="form-label">Model</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="barcode" v-model="product.barcode">
                        <label class="form-label">Barcode</label>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0">Category</p>
                        <select class="form-control custom-select select2" id="category_id">
                            <option v-for="(item, index) in categories" :value="index">{{ item }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0">Unit</p>
                        <select class="form-control custom-select select2" id="unit">
                            <option value="m">Meter (M)</option>
                            <option value="pc">Piece (Pc)</option>
                            <option value="kg">Kilogram (Kg)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="sale_price" v-model="product.sale_price">
                        <label class="form-label">Sale Price</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0">Image</p>
                        <input type="file" class="form-control" name="image" @change="imageUpload">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="details" v-model="product.description" id="details"
                                  class="form-control"></textarea>
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
        props : ['categories'],
        data () {
            return {
                product : {
                    name: '',
                    model: '',
                    category_id: '',
                    unit: '',
                    sale_price: '',
                    description: '',
                    image : '',
                    barcode : ''
                }
            }
        },
        components : {
            errors,
            submitButton
        },
        methods: {
            add () {
                this.$store.dispatch('setValidationErrors', '');
                this.$store.dispatch('setSubmitted', true);

                this.product.category_id = document.getElementById('category_id').value;
                var unit = document.getElementById('unit').value;
                if(unit) {
                    this.product.unit = unit;
                }

                this.$axios.post('products', this.product, this.$auth.getHeader()).then(response => {
                    this.product = {
                        name: '',
                        model: '',
                        category_id: '',
                        unit: '',
                        sale_price: '',
                        description: '',
                        image : '',
                        barcode : ''
                    };
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
            },
            imageUpload(e) {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = (e) => {
                    this.product.image = e.target.result;
                }
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
</style>