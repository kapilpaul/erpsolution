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
            <label class="form-label">Name</label>
          </div>
        </div>
        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="email"
              v-model="email"
            />
            <label class="form-label">Email</label>
          </div>
        </div>
        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="mobile"
              v-model="mobile"
            />
            <label class="form-label">Mobile</label>
          </div>
        </div>
        <div class="form-group form-float">
          <div class="form-line">
            <!--<input type="text" class="form-control" name="name" v-model="details">-->
            <textarea
              name="address"
              v-model="address"
              id="details"
              class="form-control"
            ></textarea>
            <label class="form-label">Address</label>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="previous_purchase_amount"
              v-model="previous_purchase_amount"
            />
            <label class="form-label">Previous Purchase Amount</label>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="form-control"
              name="balance"
              v-model="balance"
            />
            <label class="form-label">Balance</label>
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
import errors from "../errors.vue";
export default {
  data() {
    return {
      name: "",
      mobile: "",
      email: "",
      address: "",
      balance: "",
      previous_purchase_amount: "",
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
        mobile: this.mobile,
        email: this.email,
        address: this.address,
        balance: this.balance,
        previous_purchase_amount: this.previous_purchase_amount
      };

      this.$axios
        .post("customer", data, this.$auth.getHeader())
        .then(response => {
          this.submitted = false;
          this.name = this.mobile = this.email = this.address = this.previous_purchase_amount = this.balance =
            "";

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
