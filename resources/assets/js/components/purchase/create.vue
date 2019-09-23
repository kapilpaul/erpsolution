<template>
  <div>
    <div class="card-body">
      <errors></errors>
      <form class="form-material" @submit.prevent="add">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group form-float">
              <div class="form-line">
                <input
                  type="text"
                  class="form-control"
                  name="invoice_no"
                  v-model="purchase.invoice_no"
                />
                <label class="form-label">Invoice No</label>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group form-float">
              <div class="form-line">
                <input
                  type="text"
                  class="date-time-picker form-control"
                  data-options='{"timepicker":false, "format":"d-m-Y"}'
                  id="purchase_date"
                  autocomplete="off"
                />

                <label class="form-label">Date *</label>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group form-float">
              <div class="form-line">
                <input
                  type="text"
                  class="form-control"
                  v-model="purchase.from"
                />
                <label class="form-label">From</label>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group form-float">
              <div class="form-line">
                <input
                  type="text"
                  class="form-control"
                  v-model="purchase.vehicle_no"
                />
                <label class="form-label">Vehicle No</label>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group form-float">
              <div class="form-line">
                <input
                  type="text"
                  class="form-control"
                  v-model="purchase.driverName"
                />
                <label class="form-label">Driver Name</label>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <textarea
              name="details"
              v-model="purchase.details"
              id="details"
              class="form-control"
            ></textarea>
            <label class="form-label">Details</label>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <p>Products</p>
          </div>
        </div>

        <transition-group
          name="custom-classes-transition"
          enter-active-class="animated slideInDown"
          leave-active-class="animated slideOutDown"
          :duration="{ leave: 200 }"
        >
          <div
            class="row"
            v-for="(item, index) in purchase.products"
            :key="index"
          >
            <div class="col-md-6">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">Product</p>

                  <v-select
                    label="name"
                    :filterable="false"
                    :options="productItems"
                    @search="onSearch"
                    v-model="purchase['products'][index].product_id"
                  >
                    <template slot="no-options"
                      >Type to search Products..</template
                    >

                    <template slot="option" slot-scope="option">
                      <div class="d-center" v-if="option.photo != null">
                        <img
                          class="img-responsive"
                          :src="productImageUrl + option.photo.photo"
                        />
                        {{ option.name }} - {{ option.model }}
                      </div>

                      <div class="d-center" v-else>
                        <img
                          class="img-responsive"
                          :src="productImageUrl + 's2.png'"
                        />
                        {{ option.name }} - {{ option.model }}
                      </div>
                    </template>
                    <template slot="selected-option" slot-scope="option">
                      <div
                        class="selected d-center"
                        v-if="option.photo != null"
                      >
                        <img
                          class="img-responsive"
                          :src="productImageUrl + option.photo.photo"
                        />
                        {{ option.name }}
                      </div>

                      <div class="selected d-center" v-else>
                        <img
                          class="img-responsive"
                          :src="productImageUrl + 's2.png'"
                        />
                        {{ option.name }}
                      </div>
                    </template>
                  </v-select>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">In Stock</p>
                  <input
                    type="number"
                    :value="purchase['products'][index].product_id.stock"
                    class="form-control"
                    name="stock"
                    disabled
                  />
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">Quantity</p>
                  <input
                    type="number"
                    class="form-control"
                    name="quantity"
                    v-model="item.quantity"
                  />
                </div>
              </div>
            </div>
            <!-- <div class="col-md-2">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">Price</p>
                  <input type="text" class="form-control" name="price" v-model="item.price" />
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">Total</p>
                  <input
                    type="text"
                    class="form-control"
                    name="total"
                    v-model="item.total = item.quantity * item.price"
                  />
                </div>
              </div>
            </div>-->
            <!-- <div class="col-md-1">
              <div class="form-group form-float">
                <p class="p-label mb-0">&nbsp;</p>
                <button
                  @click.prevent="removeProduct(index)"
                  type="button"
                  class="btn btn-sm btn-danger"
                >
                  <i class="icon-minus"></i>
                </button>
              </div>
            </div>-->
          </div>
        </transition-group>

        <!-- <div class="row">
          <div class="col-md-3">
            <button @click="addnewProduct" type="button" class="btn btn-sm btn-primary">
              <i class="icon-plus"></i>
            </button>
          </div>
          <div class="col-md-2"></div>
          <div class="col-md-2"></div>
          <div class="col-md-1"></div>
          <div class="col-md-3">
            <div class="form-group form-float">
              <div class="form-line">
                <p class="p-label mb-0">Grand Total</p>
                <input
                  type="number"
                  class="form-control"
                  name="grandtotal"
                  v-model="grandTotal"
                  disabled
                />
              </div>
            </div>
          </div>
          <div class="col-md-1"></div>
        </div>-->

        <button
          v-if="purchase['products'].length > 0 && submitted == false"
          class="btn btn-primary waves-effect"
          type="submit"
        >
          SUBMIT
        </button>
        <button
          v-else-if="purchase['products'].length > 0 && submitted == true"
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
import vSelect from "vue-select";

export default {
  props: ["suppliers"],
  data() {
    return {
      purchase: {
        invoice_no: "",
        vehicle_no: "",
        from: "",
        driverName: "",
        purchase_date: "",
        details: "",
        products: [
          {
            product_id: "",
            quantity: 0,
            price: 0,
            total: 0
          }
        ]
      },
      productItems: [],
      productImageUrl: process.env.MIX_APP_ROOT + "/assets/img/products/",
      submitted: false
    };
  },
  components: {
    errors,
    vSelect
  },
  computed: {
    grandTotal() {
      var grandtotal = 0;

      this.purchase["products"].forEach(item => {
        grandtotal += item.total;
      });

      return grandtotal;
    }
  },
  methods: {
    add() {
      this.submitted = true;
      this.$store.dispatch("setValidationErrors", "");

      //setting jquery plugins data
      this.purchase.purchase_date = document.getElementById(
        "purchase_date"
      ).value;

      //new variable to store purchasedata
      var purchaseData = {
        invoice_no: this.purchase.invoice_no,
        vehicle_no: this.purchase.vehicle_no,
        from: this.purchase.from,
        driver_name: this.purchase.driverName,
        purchase_date: this.purchase.purchase_date,
        details: this.purchase.details,
        total_amount: 0,
        products: []
      };

      //iterate item and set product id
      this.purchase["products"].forEach(item => {
        purchaseData["products"].push({
          product_id: item.product_id.id,
          quantity: item.quantity
          //   price: item.price,
          //   total: item.total
        });

        // purchaseData.total_amount += item.total;
      });

      //sending request
      this.$axios
        .post("purchase", purchaseData, this.$auth.getHeader())
        .then(response => {
          this.submitted = false;
          this.purchase = {
            invoice_no: "",
            vehicle_no: "",
            purchase_date: "",
            details: "",
            products: [
              {
                product_id: "",
                quantity: 0,
                price: 0,
                total: 0
              }
            ]
          };
          this.productItems = [];

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
    },
    addnewProduct() {
      this.purchase["products"].push({
        product_id: "",
        quantity: 0,
        price: 0,
        total: 0
      });
    },
    removeProduct(index) {
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
          this.purchase["products"].splice(index, 1);
        }
      });
    },
    onSearch(search, loading) {
      loading(true);
      this.searchProduct(loading, search, this);
    },
    searchProduct(loading, search) {
      if (search != "") {
        this.$axios
          .get("products/search/" + search, this.$auth.getHeader())
          .then(response => {
            this.productItems = response.data.products.data;
            loading(false);
          })
          .catch(error => {
            console.log(error.response);
            //                    this.$store.dispatch('setValidationErrors', error.response.data.errors);
          });
      }
    }
  }
};
</script>

<style>
.p-label {
  font-size: 12px;
  font-weight: 400;
  color: #86939e;
}
.select2-container--default .select2-search--dropdown::before {
  content: "";
}
.select2-container--default .select2-search--dropdown .select2-search__field {
  padding: 5px 10px 5px 10px;
}
.select2-container--default .select2-selection--single {
  border: 0px solid #e1e8ee;
}
.mb-10 {
  margin-bottom: 10px !important;
}
.v-select .dropdown-toggle {
  border: 0px !important;
}
</style>

<style scoped>
img {
  height: auto;
  max-width: 2.5rem;
  margin-right: 1rem;
}

.d-center {
  display: flex;
  align-items: center;
}

.selected img {
  width: auto;
  max-height: 23px;
  margin-right: 0.5rem;
}

.v-select .dropdown li {
  border-bottom: 1px solid rgba(112, 128, 144, 0.1);
}

.v-select .dropdown li:last-child {
  border-bottom: none;
}

.v-select .dropdown li a {
  padding: 10px 20px;
  width: 100%;
  font-size: 1.25em;
  color: #3c3c3c;
}

.v-select .dropdown-menu .active > a {
  color: #fff;
}
</style>
