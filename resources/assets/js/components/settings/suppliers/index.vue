<template>
    <div>
        <div class="form-group focused">
            <input class="form-control r-0" v-model="search" type="text" placeholder="Search...">
        </div>
        <div v-if="fliteredItems.length > 0">
            <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Suppliers</h6>
            </div>

            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Balance</th>
                        <th>Details</th>
                        <th style="width: 50px">Action</th>
                    </tr>

                    <tr v-for="(item, index) in fliteredItems">
                        <td>{{ index + 1 }}</td>
                        <td><a :href="url + item.code + '/details'">{{ item.name }}</a></td>
                        <td><a :href="'tel:'+item.mobile">{{ item.mobile }}</a></td>
                        <td>{{ item.address }}</td>
                        <td>{{ item.balance }}</td>
                        <td>{{ item.details }}</td>
                        <td>
                            <a href="#modal" @click="$store.dispatch('setSuppliersEditData', item)">
                                <i class="icon-edit"></i>
                            </a>
                            &nbsp;
                            <a href="#" @click="deleteItem(index, item.id)">
                                <i class="icon-close2 text-danger-o text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
            <!-- /.box-body -->

            <edit></edit>
        </div>

        <no-data :name="'Supplier'" :url="url" v-else></no-data>
    </div>
</template>

<script>
    import edit from './edit.vue';
    import noData from '../../common/nodata.vue';

    export default{
        data () {
            return {
                items : [],
                search : "",
                url : process.env.MIX_APP_URL + 'suppliers/'
            }
        },
        components : {
            edit,
            noData
        },
        computed: {
            suppliers () {
                return this.$store.getters.suppliers;
            },
            fliteredItems() {
                var search = this.search.toLowerCase();
                return this.suppliers.filter(item => {
                    if(item.name.toLowerCase().match(search) || item.code.toLowerCase().match(search) || item.mobile.toLowerCase().match(search)) {
                        return item;
                    }
                })
            }
        },
        mounted() {
            this.getItems();
        },
        methods : {
            getItems () {
                this.$store.dispatch('setSuppliers', '');
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
                        this.$axios.delete('suppliers/' + id, this.$auth.getHeader()).then(response => {
                            this.getItems();
                            this.$swal(
                                'Deleted!',
                                'Your Data has been deleted.',
                                'success'
                            );
                        });
                    }
                })
            }
        }
    }
</script>

<style>

</style>