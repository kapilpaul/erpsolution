<template>
  <div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0">Customer Mobile</p>
            <input
              type="text"
              class="form-control"
              name="mobile"
              v-model="customer.mobile"
              @input="searchCustomer"
            />
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0">Customer Name</p>
            <input
              type="text"
              class="form-control"
              name="name"
              v-model="customer.name"
              :disabled="found"
            />
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0">Customer Email</p>
            <input
              type="text"
              class="form-control"
              name="email"
              v-model="customer.email"
              :disabled="found"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      customer: {
        mobile: 0,
        name: "",
        email: ""
      },
      notfound: "",
      found: false
    };
  },
  methods: {
    searchCustomer() {
      if (this.customer.mobile !== "") {
        axios
          .get(
            "customer/mobile/" + this.customer.mobile + "/show",
            this.$auth.getHeader()
          )
          .then(response => {
            if (response.data.notfound) {
              this.found = false;
              this.notfound = response.data.notfound;
              this.customer.name = this.customer.email = "";
            } else {
              this.found = true;
              let customerData = response.data.customer;
              this.customer.name = customerData.name;
              this.customer.email = customerData.email;
            }
            this.$emit("customer", this.customer);
          });
      } else {
        this.customer.number = this.customer.name = this.customer.email = "";
      }
    }
  }
};
</script>

<style></style>
