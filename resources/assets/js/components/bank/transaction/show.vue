<template>
  <div>
    <div class="card mb-3 shadow no-b r-0 mt-3">
      <div class="card-header white">
        <h6>Bank Transaction Details</h6>
      </div>

      <div class="box-body no-padding">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th style="width: 10px">#</th>
              <th>Date</th>
              <th v-if="code == ''">Bank Name</th>
              <th>Description</th>
              <th>Withdraw / Deposit ID</th>
              <th>Debit (+)</th>
              <th>Credit (-)</th>
              <th>Balance</th>
              <th style="width: 50px">Action</th>
            </tr>

            <tr v-for="(item, index) in items.data">
              <td>{{ index + 1 }}</td>
              <td>{{ item.date }}</td>
              <td v-if="typeof item.bank != 'undefined'">
                {{ item.bank.name }}
              </td>
              <td>{{ item.description }}</td>
              <td>{{ item.slip_id }}</td>
              <td>{{ item.debit }}</td>
              <td>{{ item.credit }}</td>
              <td>
                <div v-if="item.debit != 0">
                  {{ item.debit }}
                </div>
                <div v-if="item.credit != 0">
                  {{ item.credit }}
                </div>
              </td>

              <td>
                <a
                  href="#modal"
                  @click="$store.dispatch('setBankTransactionEditData', item)"
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
              <th>Grand Total</th>
              <th class="text-black">{{ debit }}</th>
              <th class="text-black">{{ credit }}</th>
              <th class="text-black">{{ grandTotal }}</th>
              <th></th>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->

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

    <edit :code="code"></edit>
  </div>
</template>

<script>
import paginate from "vuejs-paginate";
import edit from "./edit.vue";

export default {
  props: ["code"],
  data() {
    return {
      items: [],
      search: "",
      page: 1,
      pageCount: 2
    };
  },
  components: {
    edit,
    paginate
  },
  computed: {
    bankTransactions() {
      this.items = this.$store.getters.bankTransactions;
      this.pageCount = this.items.last_page ? this.items.last_page : 2;
      return this.items;
    },
    debit() {
      let total = 0;

      if (typeof this.bankTransactions.data != "undefined") {
        let trans = this.bankTransactions.data;
        trans.forEach(item => {
          total += item.debit;
        });
      }
      return total;
    },
    credit() {
      let total = 0;

      if (typeof this.bankTransactions.data != "undefined") {
        let trans = this.bankTransactions.data;
        trans.forEach(item => {
          total += item.credit;
        });
      }
      return total;
    },
    grandTotal() {
      let total = 0;

      if (typeof this.bankTransactions.data != "undefined") {
        total += this.debit - this.credit;
      }
      return total;
    }
  },
  mounted() {
    this.getItems();
  },
  methods: {
    getItems() {
      if (this.code != "") {
        this.$store.dispatch("setBankTransactions", { code: this.code });
      } else {
        this.$store.dispatch("setBankTransactions", "");
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
            .delete("banks/transaction/" + id, this.$auth.getHeader())
            .then(response => {
              this.items.data.splice(index, 1);
              this.$swal("Deleted!", "Your Data has been deleted.", "success");
            });
        }
      });
    },
    fetchMore(pagenum) {
      this.page = pagenum;
      this.$store.dispatch("setBankTransactions", {
        code: this.code,
        pagenum: pagenum
      });
    }
  }
};
</script>

<style></style>
