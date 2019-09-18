<template>
  <div>
    <api-search :setLocation="'setPurchases'"></api-search>

    <div v-if="typeof purchases.data != 'undefined' && purchases.data.length > 0">
      <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
          <h6>Purchases</h6>
        </div>

        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th style="width: 10px">#</th>
                <th>Invoice No</th>
                <th>Vehicle No</th>
                <th>Purchase ID</th>
                <!-- <th>Supplier Name</th> -->
                <th>Purchase Date</th>
                <!-- <th>Total Amount</th> -->
                <th style="width: 50px">Action</th>
              </tr>

              <tr v-for="(item, index) in purchases.data" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  <a :href="purchaseHrefLink + item.purchase_no + '/show'">{{ item.invoice_no }}</a>
                </td>
                <td>{{ item.vehicle_no }}</td>
                <td>
                  <a :href="purchaseHrefLink + item.purchase_no + '/show'">{{ item.purchase_no }}</a>
                </td>
                <!-- <td>
                  <a
                    :href="supplierHrefLink + item.supplier.code + '/details'"
                  >{{ item.supplier.name }}</a>
                </td>-->
                <td>{{ item.purchase_date }}</td>
                <!-- <td>{{ item.total_amount }}</td> -->
                <td>
                  <a :href="purchaseHrefLink + item.purchase_no + '/edit'">
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
    </div>
    <no-data :name="'Purchase'" :url="purchaseHrefLink" v-else></no-data>
  </div>
</template>

<script>
import paginate from "vuejs-paginate";
import apiSearch from "../search/apiSearch.vue";
import noData from "../common/nodata.vue";

export default {
  data() {
    return {
      items: [],
      page: 1,
      pageCount: 2,
      purchaseHrefLink: process.env.MIX_APP_ROOT + "/purchase/",
      supplierHrefLink: process.env.MIX_APP_ROOT + "/suppliers/"
    };
  },
  components: {
    paginate,
    apiSearch,
    noData
  },
  computed: {
    purchases() {
      this.items = this.$store.getters.purchases;
      this.pageCount = this.items.last_page ? this.items.last_page : 2;
      return this.items;
    }
  },
  mounted() {
    this.getItems();
  },
  methods: {
    getItems() {
      this.$store.dispatch("setPurchases", "");
    },
    deleteItem(index, id) {
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.$axios
            .delete("purchase/" + id, this.$auth.getHeader())
            .then(response => {
              this.$store.dispatch("setPurchases", "");
              this.$swal("Deleted!", "Your Data has been deleted.", "success");
            });
        }
      });
    },
    fetchMore(pagenum) {
      this.page = pagenum;
      this.$store.dispatch("setPurchases", pagenum);
    }
  }
};
</script>

<style>
</style>