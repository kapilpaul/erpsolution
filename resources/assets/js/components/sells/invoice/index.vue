<template>
  <div>
    <api-search :setLocation="'setInvoices'"></api-search>

    <div v-if="typeof invoices.data !== 'undefined' && invoices.data.length > 0">
      <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
          <h6>Invoices</h6>
        </div>

        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th style="width: 10px">#</th>
                <th>Invoice No</th>
                <th>Customer Name</th>
                <th>Vehicle No</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Discount</th>
                <th>Grand Total</th>
                <th style="width: 50px">Action</th>
              </tr>

              <tr v-for="(item, index) in invoices.data" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  <a :href="invoiceHrefLink + item.invoice_no + '/show'">{{
                    item.invoice_no
                  }}</a>
                </td>
                <td v-if="typeof item.customer !== 'undefined'">
                  <a
                    :href="customerHrefLink + item.customer.code + '/details'"
                    >{{ item.customer.name }}</a>
                </td>
                <td v-if="typeof item.customer_name !== 'undefined'">
                  <a
                    :href="customerHrefLink + item.customer_code + '/details'"
                    >{{ item.customer_name }}</a>
                </td>
                <td>{{ item.vehicle_no }}</td>
                <td>{{ item.destination }}</td>
                <td>{{ item.date }}</td>
                <td>{{ item.total_amount }}</td>
                <td>{{ item.total_discount }}</td>
                <td>{{ item.grand_total }}</td>
                <td>
                  <a :href="invoiceHrefLink + item.invoice_no + '/edit'">
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
    <no-data :name="'Purchase'" :url="invoiceHrefLink" v-else></no-data>
  </div>
</template>

<script>
import paginate from "vuejs-paginate";
import apiSearch from "../../search/apiSearch.vue";
import noData from "../../common/nodata.vue";

export default {
  data() {
    return {
      items: [],
      page: 1,
      pageCount: 2,
      invoiceHrefLink: process.env.MIX_APP_ROOT + "/invoice/",
      customerHrefLink: process.env.MIX_APP_ROOT + "/customer/"
    };
  },
  components: {
    paginate,
    apiSearch,
    noData
  },
  computed: {
    invoices() {
      this.items = this.$store.getters.invoices;
      this.pageCount = this.items.last_page ? this.items.last_page : 2;
      return this.items;
    }
  },
  mounted() {
    this.getItems();
  },
  methods: {
    getItems() {
      this.$store.dispatch("setInvoices", "");
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
            .delete("invoice/" + id, this.$auth.getHeader())
            .then(response => {
              this.$store.dispatch("setInvoices", "");
              this.$swal("Deleted!", "Your Data has been deleted.", "success");
            });
        }
      });
    },
    fetchMore(pagenum) {
      this.page = pagenum;
      this.$store.dispatch("setInvoices", pagenum);
    }
  }
};
</script>

<style></style>
