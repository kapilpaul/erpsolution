<template>
  <div>
    <div class="form-group focused">
      <input
        class="form-control r-0"
        v-model="search"
        type="text"
        placeholder="Search..."
      />
    </div>

    <div v-if="fliteredItems.length > 0">
      <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
          <h6>Banks</h6>
        </div>

        <div class="box-body no-padding">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>A/C Name</th>
                <th>A/C Number</th>
                <th>Branch</th>
                <th>Balance</th>
                <th style="width: 50px">Action</th>
              </tr>

              <tr v-for="(item, index) in fliteredItems">
                <td>{{ index + 1 }}</td>
                <td>
                  <a :href="bankDetailsUrl + item.code + '/show'">{{
                    item.name
                  }}</a>
                </td>
                <td>{{ item.ac_name }}</td>
                <td>{{ item.ac_no }}</td>
                <td>{{ item.branch }}</td>
                <td>{{ item.balance }}</td>
                <td>
                  <a
                    href="#modal"
                    @click="$store.dispatch('setBankEditData', item)"
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
                <th>Grand Total</th>
                <th class="text-black">{{ grandTotal }}</th>
                <th></th>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <edit></edit>
    </div>

    <no-data :name="'Bank'" :url="bankDetailsUrl" v-else></no-data>
  </div>
</template>

<script>
import edit from "./edit.vue";
import noData from "../../common/nodata.vue";

export default {
  data() {
    return {
      items: [],
      search: "",
      bankDetailsUrl: process.env.MIX_APP_URL + "banks/"
    };
  },
  components: {
    edit,
    noData
  },
  computed: {
    banks() {
      return this.$store.getters.banks;
    },
    fliteredItems() {
      var search = this.search.toLowerCase();
      return this.banks.filter(item => {
        if (
          item.name.toLowerCase().match(search) ||
          item.ac_name.toLowerCase().match(search) ||
          item.ac_no.toLowerCase().match(search) ||
          item.branch.toLowerCase().match(search)
        ) {
          return item;
        }
      });
    },
    grandTotal() {
      let total = 0;

      if (typeof this.banks != "undefined") {
        this.banks.forEach(item => {
          total += item.balance;
        });
      }
      return total;
    }
  },
  mounted() {
    this.getItems();
  },
  methods: {
    getItems() {
      this.$store.dispatch("setBanks", "");
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
            .delete("banks/" + id, this.$auth.getHeader())
            .then(response => {
              this.$swal("Deleted!", "Your Data has been deleted.", "success");
              this.items.splice(index, 1);
            });
        }
      });
    }
  }
};
</script>

<style></style>
