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
          <h6>Accounts</h6>
        </div>

        <div class="box-body no-padding">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Created</th>
                <th>Last Updated</th>
                <th style="width: 50px">Action</th>
              </tr>

              <tr v-for="(item, index) in fliteredItems" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  <a :href="rootUrl + item.code + '/show'">{{ item.name }}</a>
                </td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.updated_at }}</td>
                <td>
                  <a
                    href="#modal"
                    @click="
                      $store.dispatch('setAccountEditData', {
                        id: item.id,
                        name: item.name
                      })
                    "
                  >
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
        <!-- /.box-body -->
      </div>

      <edit></edit>
    </div>

    <no-data :name="'Account'" :url="url" v-else></no-data>
  </div>
</template>

<script>
import edit from "./edit.vue";
import noData from "../../../common/nodata.vue";
export default {
  data() {
    return {
      items: [],
      search: "",
      url: process.env.MIX_APP_URL + "accounts/",
      rootUrl: process.env.MIX_APP_ROOT + "/accounts/"
    };
  },
  components: {
    edit,
    noData
  },
  computed: {
    accounts() {
      return this.$store.getters.accounts;
    },
    fliteredItems() {
      var search = this.search.toLowerCase();
      return this.accounts.filter(item => {
        return item.name.toLowerCase().match(search);
      });
    }
  },
  mounted() {
    this.getItems();
  },
  methods: {
    getItems() {
      this.$store.dispatch("setAccounts", "");
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
            .delete("accounts/" + id, this.$auth.getHeader())
            .then(response => {
              this.getItems();
              this.$swal("Deleted!", "Your Data has been deleted.", "success");
            });
        }
      });
    }
  }
};
</script>

<style></style>
