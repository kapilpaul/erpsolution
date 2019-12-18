<template>
  <div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0">Customer</p>
            <!--<input-->
            <!--type="text"-->
            <!--class="form-control"-->
            <!--name="mobile"-->
            <!--v-model="customer.mobile"-->
            <!--@input="searchCustomer"-->
            <!--/>-->

            <select
              class="form-control"
              v-model="customer"
              @change="searchCustomer(customer)"
            >
              <option
                v-for="(itemCustomer, index) in customers"
                :key="index"
                :value="itemCustomer"
                >{{ itemCustomer.name }}</option
              >
            </select>
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
        email: "",
        code: ""
      },
      notfound: "",
      found: false
    };
  },
  mounted() {
    this.getItems();
  },
  computed: {
    customers() {
      let customers = this.$store.getters.customers;
      return customers.data;
    }
  },
  methods: {
    getItems() {
      this.$store.dispatch("setCustomers", "");
    },
    searchCustomer(item) {
      if (this.customer.mobile !== "") {
        // axios
        //   .get(
        //     "customer/mobile/" + this.customer.mobile + "/show",
        //     this.$auth.getHeader()
        //   )
        //   .then(response => {
        //     if (response.data.notfound) {
        //       this.found = false;
        //       this.notfound = response.data.notfound;
        //       this.customer.name = this.customer.email = "";
        //     } else {
        //       this.found = true;
        //       let customerData = response.data.customer;
        //       this.customer.name = customerData.name;
        //       this.customer.email = customerData.email;
        //     }
        //     this.$emit("customer", this.customer);
        //   });

        this.found = true;
        this.customer = {
          mobile: item.mobile,
          name: item.name,
          email: item.email,
          code: item.code
        };
        this.$emit("customer", this.customer);
      } else {
        this.customer.number = this.customer.name = this.customer.email = "";
      }
    }
  }
};
</script>

<style></style>
