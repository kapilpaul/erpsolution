<template>
  <div>
    <div class="card-body">
      <errors></errors>
      <form class="form-material" @submit.prevent="add">
        <!-- <div class="form-group form-float">
                    <div class="form-line">
                        <p class="p-label mb-0 edit-p-label">Supplier</p>
                        <select class="form-control custom-select" id="supplier_id" v-model="purchase.supplier_id">
                            <option v-for="(item, index) in suppliers" :value="index">{{ item }}</option>
                        </select>
                    </div>
        </div>-->

        <div class="row">
          <div class="col-md-6">
            <div class="form-group form-float">
              <div class="form-line">
                <p class="p-label mb-0 edit-p-label">Invoice No</p>
                <input
                  type="text"
                  class="form-control"
                  name="invoice_no"
                  v-model="purchase.invoice_no"
                />
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group form-float">
              <div class="form-line">
                <p class="p-label mb-0 edit-p-label">Date *</p>
                <input
                  type="text"
                  class="date-time-picker form-control"
                  data-options="{'timepicker':false, 'format':'d-m-Y'}"
                  id="purchase_date"
                  :value="purchase.purchase_date"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0 edit-p-label">Vehicle No</p>
            <input
              type="text"
              name="details"
              v-model="purchase.vehicle_no"
              id="vehicle_no"
              class="form-control"
            />
          </div>
        </div>

        <div class="form-group form-float">
          <div class="form-line">
            <p class="p-label mb-0 edit-p-label">Details</p>
            <textarea name="details" v-model="purchase.details" id="details" class="form-control"></textarea>
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
          <div class="row" v-for="(item, index) in purchase.products" :key="index">
            <div class="col-md-6">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">Product</p>
                  <select
                    class="form-control"
                    v-model="purchase['products'][index].pivot.product_id"
                    @change="setProductStock(index)"
                  >
                    <option
                      v-for="productItem in productItems"
                      :value="productItem.id"
                      :key="productItem.id"
                    >{{ productItem.name }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">In Stock</p>
                  <input
                    type="number"
                    class="form-control"
                    name="stock"
                    disabled
                    v-model="purchase['products'][index].stock"
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
                    v-model="item.pivot.quantity"
                  />
                </div>
              </div>
            </div>
            <!-- <div class="col-md-2">
              <div class="form-group form-float">
                <div class="form-line">
                  <p class="p-label mb-0">Price</p>
                  <input type="text" class="form-control" name="price" v-model="item.pivot.price" />
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
                    v-model="item.pivot.total =
                                    item.pivot.quantity * item.pivot.price"
                  />
                </div>
              </div>
            </div>
            <div class="col-md-1">
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

        <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
      </form>
    </div>
  </div>
</template>

<script>
import errors from "../errors.vue";
import vSelect from "vue-select";

export default {
  props: ["suppliers", "purchaseno", "productItems"],
  data() {
    return {
      //purchase : {},
      productImageUrl: process.env.MIX_APP_URL + "assets/img/products/"
    };
  },
  components: {
    errors,
    vSelect
  },
  mounted() {
    this.getItem();
  },
  computed: {
    grandTotal() {
      var grandtotal = 0;
      let items = this.purchase.products;

      if (typeof items != "undefined") {
        items.forEach(item => {
          grandtotal += Number(item.pivot.price * item.pivot.quantity);
        });
      }

      return grandtotal;
    },
    purchase() {
      return this.$store.getters.purchasesEditData;
    }
  },
  methods: {
    add() {
      this.$store.dispatch("setValidationErrors", "");

      //setting jquery plugins data
      this.purchase.purchase_date = document.getElementById(
        "purchase_date"
      ).value;

      //new variable to store purchasedata
      var purchaseData = {
        invoice_no: this.purchase.invoice_no,
        purchase_date: this.purchase.purchase_date,
        details: this.purchase.details,
        total_amount: 0,
        products: []
      };

      //iterate item and set product id
      this.purchase["products"].forEach(item => {
        purchaseData["products"].push({
          product_id: item.pivot.product_id,
          quantity: item.pivot.quantity,
          price: item.pivot.price,
          total: item.pivot.total
        });

        // purchaseData.total_amount += item.total;
      });

      //sending request
      this.$axios
        .patch(
          "purchase/" + this.purchaseno,
          purchaseData,
          this.$auth.getHeader()
        )
        .then(response => {
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
    },
    addnewProduct() {
      this.purchase["products"].push({
        pivot: {
          product_id: "",
          quantity: "0",
          price: 0,
          total: 0
        },
        stock: "0",
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
    getItem() {
      this.$store.dispatch("setPurchasesEditData", this.purchaseno);
    },
    setProductStock(index) {
      var id = this.purchase["products"][index].pivot.product_id;

      this.$axios
        .get("products/" + id + "/show", this.$auth.getHeader())
        .then(response => {
          this.purchase["products"][index].stock = response.data.product.stock;
        });
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