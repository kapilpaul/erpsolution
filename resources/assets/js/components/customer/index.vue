<template>
  <div>
    <api-search :setLocation="'setCustomers'"></api-search>

    <div
      v-if="typeof customers.data != 'undefined' && customers.data.length > 0"
    >
      <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
          <h6>Customers</h6>
        </div>

        <div class="box-body no-padding">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Balance</th>
                <th style="width: 50px">Action</th>
              </tr>

              <tr
                v-for="(item, index) in customers.data"
                :key="item.id"
                :style="[
                  item.balance > 0
                    ? { background: 'rgba(251, 69, 0, 0.45)', color: '#000' }
                    : ''
                ]"
              >
                <td>{{ index + 1 }}</td>
                <td>
                  <a :href="url + item.code + '/details'">{{ item.name }}</a>
                </td>
                <td>{{ item.email }}</td>
                <td>
                  <a :href="'tel:' + item.mobile">{{ item.mobile }}</a>
                </td>
                <td>{{ item.address }}</td>
                <td>{{ item.balance }}</td>
                <td>
                  <a
                    href="#modal"
                    @click="$store.dispatch('setCustomerEditData', item)"
                  >
                    <i class="icon-edit"></i>
                  </a>
                  &nbsp;
                  <a href="#" @click="deleteItem(index, item.id)">
                    <i class="icon-close2 text-danger-o text-danger"></i>
                  </a>
                </td>
              </tr>

              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total Dues</th>
                <th>{{ totalDue }}</th>
                <th></th>
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
      <edit></edit>
    </div>

    <no-data :name="'Customer'" :url="url" v-else></no-data>
  </div>
</template>

<script>
import edit from "./edit.vue";
import paginate from "vuejs-paginate";
import apiSearch from "../search/apiSearch.vue";
import noData from "../common/nodata.vue";

export default {
  props: {
    dues: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      items: [],
      page: 1,
      pageCount: 2,
      url: process.env.MIX_APP_ROOT + "/customer/"
    };
  },
  components: {
    edit,
    paginate,
    apiSearch,
    noData
  },
  computed: {
    customers() {
      this.items = this.$store.getters.customers;
      this.pageCount = this.items.last_page ? this.items.last_page : 2;
      return this.items;
    },
    totalDue() {
      return this.$store.getters.totalDue;
    }
  },
  mounted() {
    this.getItems();
  },
  methods: {
    getItems() {
      if (this.dues) {
        this.$store.dispatch("setCustomers", { url: "/due-customers" });
      } else {
        this.$store.dispatch("setCustomers", "");
      }
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
            .delete("customer/" + id, this.$auth.getHeader())
            .then(response => {
              this.$store.dispatch("setCustomers", "");
              this.$swal("Deleted!", "Your Data has been deleted.", "success");
            });
        }
      });
    },
    fetchMore(pagenum) {
      this.page = pagenum;
      this.$store.dispatch("setCustomers", pagenum);
    }
  }
};
</script>

<style></style>
