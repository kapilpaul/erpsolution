export const customerStore = {
    state : {
        customers : [],
        customerEditData : []
    },
    getters : {
        customers : state => {
            return state.customers;
        },
        customerEditData : state => {
            return state.customerEditData;
        }
    },
    mutations : {
        setCustomers : (state, payload) => {
            state.customers = payload;
        },
        setCustomerEditData : (state, payload) => {
            state.customerEditData = payload;
        }
    },
    actions : {
        setCustomers : ({ commit }, payload) => {
            var customerUrl = process.env.MIX_APP_URL + 'customer';

            if(payload == '') {
                customerUrl = customerUrl;
            } else if(payload.search) {
                customerUrl = customerUrl + '/search/' + payload.search;
            } else {
                customerUrl = customerUrl + "?page=" + payload;
            }

            axios.get(customerUrl, Vue.auth.getHeader()).then(response => {
                commit('setCustomers', response.data.customers);
            });
        },
        setCustomerEditData : ({ commit }, payload) => {
            commit('setCustomerEditData', payload);
        }
    }
};
