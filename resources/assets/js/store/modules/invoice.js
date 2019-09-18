export const invoiceStore = {
    state : {
        invoices : [],
        invoiceEditData : []
    },
    getters : {
        invoices : state => {
            return state.invoices;
        },
        invoiceEditData : state => {
            return state.invoiceEditData;
        }
    },
    mutations : {
        setInvoices : (state, payload) => {
            state.invoices = payload;
        },
        setInvoiceEditData : (state, payload) => {
            state.invoiceEditData = payload;
        }
    },
    actions : {
        setInvoices : ({ commit }, payload) => {
            var url = process.env.MIX_APP_URL + 'invoice';
            if(payload == '') {
                url = url;
            } else if(payload.search) {
                url = url + '/search/' + payload.search;
            } else {
                url = url + "?page=" + payload;
            }

            axios.get(url, Vue.auth.getHeader()).then(response => {
                commit('setInvoices', response.data.invoices);
            }).catch(error =>  {
                console.log(error.response);
            });

        },
        setInvoiceEditData : ({ commit }, payload) => {
            var invoiceEditUrl = process.env.MIX_APP_URL + 'invoice/' + payload + '/edit';
            axios.get(invoiceEditUrl, Vue.auth.getHeader()).then(response => {
                commit('setInvoiceEditData', response.data.invoice);
            }).catch(error =>  {
                console.log(error.response);
            });
        }
    }
};
