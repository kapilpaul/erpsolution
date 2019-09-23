<template>
    <div>
        <api-search :setLocation="setLocation"></api-search>
        <div class="card mb-3 shadow no-b r-0">
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Product Name</th>
                        <th>Sale Price</th>
                        <th>In Qnty.</th>
                        <th>Out Qnty.</th>
                        <th>Stock</th>
                        <!--<th>Stock Sale Price</th>-->
                    </tr>

                    <tr v-for="(item, index) in inStock.data">
                        <td>{{ index + 1 }}</td>
                        <td><a :href="productUrl + item.code + '/details'">{{ item.name }} - {{ item.model }}</a></td>
                        <td>{{ item.sale_price }}</a></td>
                        <td>{{ item.in_quantity }}</td>
                        <td>{{ item.out_quantity }}</td>
                        <td>{{ item.stock }}</td>
                        <!--<td>{{ Math.round(item.stock * item.sale_price) }}</td>-->
                    </tr>

                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Grand Total</th>
                        <!--<th class="text-black">{{ grandTotal }}</th>-->
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
        props : ['setLocation'],
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
            inStock () {
                this.items = this.$store.getters.inStock;
                this.pageCount = this.items.last_page ? this.items.last_page : 2;
                return this.items;
            },
            grandTotal () {
                let total = 0;

                if(typeof this.inStock.data != 'undefined') {
                    this.inStock.data.forEach(item => {
                        total += Math.round(item.stock * item.sale_price);
                    });
                }
                return total;
            }
        },
        mounted() {
            this.getItems();
        },
        methods : {
            getItems () {
                this.$store.dispatch(this.setLocation, '');
            },
            fetchMore(pagenum) {
                this.page = pagenum;
                this.$store.dispatch(this.setLocation, pagenum);
            }
        }
    }
</script>

<style>
    td{
        color: #333;
    }
</style>
