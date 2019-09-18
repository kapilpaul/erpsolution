<template>
  <div>
    <div class="card-body">
      <errors></errors>
      <form class="form-material" @submit.prevent="add">
        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="name"
              v-model="name"
            />
            <label class="form-label">Bank Name *</label>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="ac_name"
              v-model="ac_name"
            />
            <label class="form-label">A/C Name *</label>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="ac_no"
              v-model="ac_no"
            />
            <label class="form-label">A/C Number *</label>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="branch"
              v-model="branch"
            />
            <label class="form-label">Branch *</label>
          </div>
        </div>

        <button
          v-if="submitted == false"
          class="btn btn-primary waves-effect"
          type="submit"
        >
          SUBMIT
        </button>
        <button
          v-else-if="submitted == true"
          class="btn btn-primary waves-effect"
          disabled
        >
          <i class="fa fa-spinner fa-spin"></i>
          Processing
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import errors from "../../errors.vue";
export default {
  data() {
    return {
      name: "",
      ac_name: "",
      ac_no: "",
      branch: "",
      submitted: false
    };
  },
  components: {
    errors
  },
  methods: {
    add() {
      this.submitted = true;
      this.$store.dispatch("setValidationErrors", "");

      var data = {
        name: this.name,
        ac_name: this.ac_name,
        ac_no: this.ac_no,
        branch: this.branch
      };

      this.$axios
        .post("banks", data, this.$auth.getHeader())
        .then(response => {
          this.submitted = false;
          this.name = this.ac_name = this.ac_no = this.branch = "";
          this.$swal({
            title: "Success!",
            text: response.data.success,
            type: "success",
            confirmButtonText: "Cool"
          });
        })
        .catch(error => {
          this.submitted = false;
          this.$store.dispatch(
            "setValidationErrors",
            error.response.data.errors
          );
        });
    }
  }
};
</script>

<style></style>
