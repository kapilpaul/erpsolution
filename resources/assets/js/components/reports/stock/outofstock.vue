<template>
    <div>
        <api-search :setLocation="'setOutOfStock'"></api-search>

        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Out Of Stock</h6>
            </div>

            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Stock</th>
                    </tr>

                    <tr v-for="(item, index) in outOfStock.data">
                        <td>{{ index + 1 }}</td>
                        <td><a :href="productUrl + item.code + '/details'">{{ item.name }} - {{ item.model }}</a></td>
                        <td>{{ item.unit }}</td>
                        <td>
                            <span :class="{'badge badge-danger r-3' : item.stock == 0, 'badge badge-primary r-3' : item.stock > 0}">{{ item.stock }}</span>
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
        <!-- /.box-body -->
    </div>
</template>

<script>
    import paginate from 'vuejs-paginate'
    import apiSearch from '../../search/apiSearch.vue'

    export default{
        data () {
            return {
                items : [],
                search : "",
                page : 1,
                pageCount : 2,
                productUrl : process.env.MIX_APP_URL + 'products/'
            }
        },
        components : {
            paginate,
            apiSearch
        },
        computed: {
            outOfStock () {
                this.items = this.$store.getters.outOfStock;
                this.pageCount = this.items.last_page ? this.items.last_page : 2;
                return this.items;
            }
        },
        mounted() {
            this.getItems();
        },
        methods : {
            getItems () {
                this.$store.dispatch('setOutOfStock', '');
            },
            fetchMore(pagenum) {
                this.page = pagenum;
                this.$store.dispatch('setOutOfStock', pagenum);
            }
        }
    }
</script>

<style>

</style>