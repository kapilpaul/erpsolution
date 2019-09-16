<template>
    <div>
        <api-search :setLocation="'setProducts'"></api-search>
        <div v-if="typeof products.data != 'undefined' && products.data.length > 0">
            <div class="card mb-3 shadow no-b r-0">
                <div class="card-header white">
                    <h6>Products</h6>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover ">
                        <tbody>
                        <tr v-for="(item, index) in products.data">
                            <td class="w-10">
                                <div v-if="item.photo_id != null">
                                    <div v-if="item.photo != null">
                                        <img :src="$siteurl + 'assets/img/products/' + item.photo.photo" alt="">
                                    </div>
                                </div>
                                <div v-else>
                                    <img :src="$siteurl + 'assets/img/demo/shop/s1.png'" alt="">
                                </div>
                                <!--<img src="assets/img/demo/shop/s1.png" alt="">-->
                            </td>
                            <td>
                                <h6><a :href="productUrl + item.code + '/details'">{{ item.name }} - {{ item.model }}
                                </a></h6>
                                <small class="text-muted">Barcode: {{ item.barcode }}</small>
                                <br>
                                <small class="text-muted">
                                    <div v-if="item.category">
                                        {{ item.category.name }}
                                    </div>
                                </small>

                                <small class="text-muted">
                                    Unit : {{ item.unit }}
                                </small>
                            </td>
                            <td>
                                <span><i class="icon icon-data_usage"></i> {{ item.sale_price }}</span><br>
                                <span><i class="icon icon-timer"></i> {{ item.price }}</span>
                            </td>
                            <td>
                            <span class="badge"
                                  :class="{'badge-success' : (item.stock > 0), 'badge-danger': (item.stock <= 0)}"
                            >
                                Stock : {{ item.stock }}</span>
                            </td>
                            <td>
                                <a class="btn-fab btn-fab-sm btn-primary shadow text-white" href="#modal" @click="$store.dispatch('setProductsEditData', item)">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a class="btn-fab btn-fab-sm btn-danger shadow text-white" href="#" @click.prevent="deleteItem(index, item.id)">
                                    <i class="icon-close2 text-danger-o text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12" v-if="pageCount > 1">
                    <paginate
                            v-model="page"
                            :page-count="pageCount"
                            :click-handler="fetchMore"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination float-right'"
                            :page-class="'page-item'"
                            :page-link-class="'page-link'"
                            :prev-class="'page-item'"
                            :prev-link-class="'page-link'"
                            :next-class="'page-item'"
                            :next-link-class="'page-link'"
                    ></paginate>
                </div>
            </div>
            <edit :categories="categories"></edit>
        </div>

        <no-data :name="'Product'" :url="productUrl" v-else></no-data>
    </div>
</template>

<script>
    import paginate from 'vuejs-paginate'
    import edit from './edit.vue';
    import apiSearch from '../../search/apiSearch.vue';
    import noData from '../../common/nodata.vue';

    export default{
        props : ['categories'],
        data () {
            return {
                items : [],
                page : 1,
                pageCount : 2,
                productUrl : process.env.MIX_APP_URL + 'products/'
            }
        },
        components : {
            edit,
            paginate,
            apiSearch,
            noData
        },
        computed: {
            products () {
                this.items = this.$store.getters.products;
                this.pageCount = this.items.last_page ? this.items.last_page : 2;
                return this.items;
            }
        },
        mounted() {
            this.getItems();
        },
        methods : {
            getItems () {
                this.$store.dispatch('setProducts', '');
            },
            deleteItem(index, id) {
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
                        this.$axios.delete('products/' + id, this.$auth.getHeader()).then(response => {
                            this.$store.dispatch('setProducts', '');
                            this.$swal(
                                'Deleted!',
                                'Your Data has been deleted.',
                                'success'
                            );
                        });
                    }
                })
            },
            fetchMore(pagenum) {
                this.page = pagenum;
                this.$store.dispatch('setProducts', pagenum);
            }
        }
    }
</script>

<style>

</style>