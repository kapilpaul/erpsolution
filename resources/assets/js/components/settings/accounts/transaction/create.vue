<template>
  <div>
    <div class="card-body">
      <errors></errors>
      <form class="form-material" @submit.prevent="add">
        <div class="form-group form-float">
          <div class="form-line">
            <input
              type="text"
              class="date-time-picker form-control"
              data-options='{"timepicker":false, "format":"d-m-Y"}'
              id="date"
            />
            <label class="form-label">Date *</label>
          </div>
        </div>

        <div class="row">
          <div class="col-md-5">
            <input-select
              label="Category Type *"
              name="category_type"
              :options="categories"
              @selectedValue="getReceivers"
            ></input-select>
          </div>
          <div class="col-md-7">
            <div class="form-group form-float">
              <div class="form-line">
                <p class="p-label mb-0">Select {{ itemData.category }} *</p>
                <v-select
                  label="name"
                  :options="receivers"
                  v-model="itemData.receiver_id"
                ></v-select>
              </div>
            </div>
          </div>
        </div>

        <input-select
          label="Transaction Mode *"
          name="tmode"
          :options="tmodes"
          v-model="itemData.tmode"
        ></input-select>

        <transition
          enter-active-class="animated fadeIn"
          leave-active-class="animated fadeOut"
        >
          <div
            v-if="itemData.tmode === 'cheque' || itemData.tmode === 'payorder'"
          >
            <div class="row">
              <div class="col-md-6">
                <input-text
                  name="slip_id"
                  label="Cheque/Payorder No *"
                  v-model="chequeDetails.slip_id"
                ></input-text>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <div class="form-line">
                    <p class="p-label mb-0">Date *</p>
                    <input
                      type="date"
                      class="form-control"
                      v-model="chequeDetails.date"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group form-float">
              <div class="form-line">
                <p class="p-label mb-0">Bank Name *</p>
                <v-select
                  label="name"
                  :options="banks"
                  v-model="chequeDetails.bank_id"
                ></v-select>
              </div>
            </div>

            <input-radio
              name="permission_to_add_bank_transaction"
              label="Do you want to add this Transaction to your bank ?"
              :options="radioOptions"
              v-model="chequeDetails.add_bank_transaction"
            ></input-radio>
          </div>
        </transition>

        <input-text
          name="amount"
          label="Amount *"
          v-model="itemData.amount"
        ></input-text>
        <input-text-area
          name="description"
          label="Description"
          v-model="itemData.description"
        ></input-text-area>

        <submit-button></submit-button>
      </form>
    </div>
  </div>
</template>

<script>
import errors from "../../../errors.vue";
import submitButton from "../../../common/submit.vue";
import inputSelect from "../../../common/form/input-select.vue";
import inputText from "../../../common/form/input-text.vue";
import inputTextArea from "../../../common/form/input-textarea.vue";
import inputRadio from "../../../common/form/input-radio.vue";
import vSelect from "vue-select";

export default {
  props: ["banks", "transactionType"],
  data() {
    return {
      itemData: {
        type: this.transactionType,
        date: "",
        category: "",
        tmode: "cash",
        amount: "",
        description: "",
        receiver_id: ""
      },
      chequeDetails: {
        slip_id: "",
        date: "",
        bank_id: "",
        add_bank_transaction: "yes"
      },
      receivers: [],
      categories: [
        // { value: "supplier", label: "Supplier" },
        { value: "customer", label: "Customer" },
        { value: "office", label: "Office" }
        // { value: "loan", label: "Loan" }
      ],
      tmodes: [
        { value: "cash", label: "Cash" },
        { value: "cheque", label: "Cheque" },
        { value: "payorder", label: "Payorder" }
      ],
      radioOptions: [
        { value: "yes", label: "Yes", selected: true },
        { value: "no", label: "No" }
      ]
    };
  },
  components: {
    errors,
    submitButton,
    vSelect,
    inputSelect,
    inputText,
    inputTextArea,
    inputRadio
  },
  methods: {
    add() {
      this.$store.dispatch("setSubmitted", true);
      this.$store.dispatch("setValidationErrors", "");

      //setting jquery plugins data
      this.itemData.date = document.getElementById("date").value;
      let receiver = this.itemData.receiver_id;
      this.itemData.receiver_id = receiver.code;

      if (this.itemData.tmode !== "cash") {
        let bank = this.chequeDetails.bank_id;
        this.chequeDetails.bank_id = bank.code;
        this.itemData["chequeDetails"] = this.chequeDetails;
      }

      this.$axios
        .post("transactions", this.itemData, this.$auth.getHeader())
        .then(response => {
          this.$store.dispatch("setSubmitted", false);
          document.getElementById("date").value = "";
          this.itemData = {
            type: this.transactionType,
            date: "",
            category: "",
            tmode: "cash",
            amount: "",
            description: "",
            receiver_id: ""
          };

          this.$swal({
            title: "Success!",
            text: response.data.success,
            type: "success",
            confirmButtonText: "Cool"
          });
        })
        .catch(error => {
          this.$store.dispatch("setSubmitted", false);
          this.$store.dispatch(
            "setValidationErrors",
            error.response.data.errors
          );
          console.log(error.response);
          this.$swal({
            title: error.response.status + " Error!",
            text: error.response.statusText,
            type: "error",
            confirmButtonText: "Okay"
          });
        });
    },
    getReceivers(value) {
      let url;
      this.itemData.category = value;
      this.itemData.receiver_id = "";

      if (this.itemData.category === "customer") {
        url = "customer/all";
      } else if (this.itemData.category === "supplier") {
        url = "suppliers/all";
      } else if (this.itemData.category === "office") {
        url = "accounts/all";
      }

      this.$axios
        .get(url, this.$auth.getHeader())
        .then(response => {
          this.receivers = response.data.items;
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
</style>
