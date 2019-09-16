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
                        <p class="p-label mb-0 edit-p-label">Model *</p>
                        <input type="text" class="form-control" name="model" v-model="model" @keyup.enter="update">
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Barcode</p>
                        <input type="text" class="form-control" name="model" v-model="barcode" @keypress.enter.prevent="">
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Category *</p>
                        <select class="form-control" id="category_id" v-model="category_id">
                            <option v-for="(item, index) in categories" :value="index">{{ item }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Unit *</p>
                        <select class="form-control" id="unit" v-model="unit" @keyup.enter="update">
                            <option value="m">Meter (M)</option>
                            <option value="pc">Piece (Pc)</option>
                            <option value="kg">Kilogram (Kg)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Sale Price *</p>
                        <input type="text" class="form-control" name="sale_price" v-model="sale_price" @keyup.enter="update">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Image</p>
                        <input type="file" class="form-control" name="image" @change="imageUpload">
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Description</p>
                        <textarea name="details" v-model="description" id="details" class="form-control" @keyup.enter="update"></textarea>
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
        props : ['categories'],
        data () {
            return {
                image : ''
            }
        },
        components : {
            errors
        },
        computed: {
            name : {
                get() {
                    return this.$store.getters.productsEditData.name
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('name', value));
                }
            },
            model: {
                get() {
                    return this.$store.getters.productsEditData.model
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('model', value));
                }
            },
            barcode: {
                get() {
                    return this.$store.getters.productsEditData.barcode
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('barcode', value));
                }
            },
            category_id: {
                get() {
                    return this.$store.getters.productsEditData.category_id
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('category_id', value));
                }
            },
            unit: {
                get() {
                    return this.$store.getters.productsEditData.unit
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('unit', value));
                }
            },
            sale_price: {
                get() {
                    return this.$store.getters.productsEditData.sale_price
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('sale_price', value));
                }
            },
            description: {
                get() {
                    return this.$store.getters.productsEditData.description
                },
                set(value) {
                    this.$store.dispatch('setProductsEditData', this.updateValue('description', value));
                }
            },
            id() {
                return this.$store.getters.productsEditData.id
            }
        },
        methods: {
            updateValue(elem, value) {
                var data = {
                    id: this.id,
                    name: this.name,
                    model: this.model,
                    barcode: this.barcode,
                    category_id: this.category_id,
                    unit: this.unit,
                    sale_price: this.sale_price,
                    description: this.description
                };
                data[elem] = value;
                return data;
            },
            update () {
                this.$store.dispatch('setValidationErrors', '');

                var data = {
                    name: this.name,
                    model: this.model,
                    barcode: this.barcode,
                    category_id: this.category_id,
                    unit: this.unit,
                    sale_price: this.sale_price,
                    description: this.description,
                    image : this.image
                };
                var id = this.$store.getters.productsEditData.id;

                this.$axios.patch('products/' + id, data,this.$auth.getHeader()).then(response => {
                    this.image = '';
                    this.$store.dispatch('setProducts', '');

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
            },
            imageUpload(e) {
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = (e) => {
                    this.image = e.target.result;
                }
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