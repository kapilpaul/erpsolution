export const customerStore = {
  state: {
    customers: [],
    customerEditData: [],
    totalDue: 0
  },
  getters: {
    customers: state => {
      return state.customers;
    },
    customerEditData: state => {
      return state.customerEditData;
    },
    totalDue: state => {
      return state.totalDue;
    }
  },
  mutations: {
    setCustomers: (state, payload) => {
      state.customers = payload;
    },
    setCustomerEditData: (state, payload) => {
      state.customerEditData = payload;
    },
    setTotalDueData: (state, payload) => {
      state.totalDue = payload;
    }
  },
  actions: {
    setCustomers: ({ commit }, payload) => {
      var customerUrl = process.env.MIX_APP_URL + "customer";

      if (payload == "") {
        customerUrl = customerUrl;
      } else if (payload.url) {
        customerUrl = customerUrl + payload.url;
      } else if (payload.search) {
        customerUrl = customerUrl + "/search/" + payload.search;
      } else {
        customerUrl = customerUrl + "?page=" + payload;
      }

      axios.get(customerUrl, Vue.auth.getHeader()).then(response => {
        if (typeof response.data.totalDue !== "undefined") {
          commit("setTotalDueData", response.data.totalDue);
        }
        commit("setCustomers", response.data.customers);
      });
    },
    setCustomerEditData: ({ commit }, payload) => {
      commit("setCustomerEditData", payload);
    }
  }
};
