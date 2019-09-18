<template>
  <div>
    <div class="remodal" data-remodal-id="modal">
      <button data-remodal-action="close" class="remodal-close"></button>
      <h1>Edit</h1>
      <br />
      <errors></errors>
      <form class="form-material">
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0 edit-p-label">Name *</p>
            <input
              type="text"
              class="form-control"
              name="name"
              v-model="name"
              @keyup.enter="update"
            />
          </div>
        </div>
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0 edit-p-label">A/C Name *</p>
            <input
              type="text"
              class="form-control"
              name="ac_name"
              v-model="ac_name"
              @keyup.enter="update"
            />
          </div>
        </div>
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0 edit-p-label">A/C No *</p>
            <input
              type="text"
              class="form-control"
              name="ac_no"
              v-model="ac_no"
              @keyup.enter="update"
            />
          </div>
        </div>
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0 edit-p-label">Branch *</p>
            <input
              type="text"
              class="form-control"
              name="branch"
              v-model="branch"
              @keyup.enter="update"
            />
          </div>
        </div>
        <br />
        <button data-remodal-action="cancel" class="remodal-cancel">
          Cancel
        </button>
        <button class="remodal-confirm" @click.prevent="update">Update</button>
      </form>
    </div>
  </div>
</template>

<script>
import errors from "../../errors.vue";
export default {
  components: {
    errors
  },
  computed: {
    name: {
      get() {
        return this.$store.getters.bankEditData.name;
      },
      set(value) {
        this.$store.dispatch(
          "setBankEditData",
          this.updateValue("name", value)
        );
      }
    },
    ac_name: {
      get() {
        return this.$store.getters.bankEditData.ac_name;
      },
      set(value) {
        this.$store.dispatch(
          "setBankEditData",
          this.updateValue("ac_name", value)
        );
      }
    },
    ac_no: {
      get() {
        return this.$store.getters.bankEditData.ac_no;
      },
      set(value) {
        this.$store.dispatch(
          "setBankEditData",
          this.updateValue("ac_no", value)
        );
      }
    },
    branch: {
      get() {
        return this.$store.getters.bankEditData.branch;
      },
      set(value) {
        this.$store.dispatch(
          "setBankEditData",
          this.updateValue("branch", value)
        );
      }
    },
    id() {
      return this.$store.getters.bankEditData.id;
    }
  },
  methods: {
    updateValue(elem, value) {
      var data = {
        id: this.id,
        name: this.name,
        ac_name: this.ac_name,
        ac_no: this.ac_no,
        branch: this.branch
      };
      data[elem] = value;
      return data;
    },
    update() {
      this.$store.dispatch("setValidationErrors", "");

      var data = {
        name: this.name,
        ac_name: this.ac_name,
        ac_no: this.ac_no,
        branch: this.branch
      };
      var id = this.$store.getters.bankEditData.id;

      this.$axios
        .patch("banks/" + id, data, this.$auth.getHeader())
        .then(response => {
          this.$store.dispatch("setCustomers", "");

          let inst = $("[data-remodal-id=modal]");
          $("button.remodal-close", inst).click();

          this.$swal({
            title: "Success!",
            text: response.data.success,
            type: "success",
            confirmButtonText: "Cool"
          });
        })
        .catch(error => {
          this.$store.dispatch(
            "setValidationErrors",
            error.response.data.errors
          );
        });
    }
  }
};
</script>

<style>
.edit-p-label {
  text-align: left;
  font-size: 12px;
}
</style>
