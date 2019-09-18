export const purchaseStore = {
    state : {
        purchases : [],
        purchasesEditData : []
    },
    getters : {
        purchases : state => {
            return state.purchases;
        },
        purchasesEditData : state => {
            return state.purchasesEditData;
        }
    },
    mutations : {
        setPurchases : (state, payload) => {
            state.purchases = payload;
        },
        setPurchasesEditData : (state, payload) => {
            state.purchasesEditData = payload;
        }
    },
    actions : {
        setPurchases : ({ commit }, payload) => {
            var purchaseUrl = process.env.MIX_APP_URL + 'purchase';
            if(payload == '') {
                purchaseUrl = purchaseUrl;
            } else if(payload.search) {
                purchaseUrl = purchaseUrl + '/search/' + payload.search;
            } else {
                purchaseUrl = purchaseUrl + "?page=" + payload;
            }

            axios.get(purchaseUrl, Vue.auth.getHeader()).then(response => {
                commit('setPurchases', response.data.purchases);
            });

        },
        setPurchasesEditData : ({ commit }, payload) => {
            var purchaseEditUrl = process.env.MIX_APP_URL + 'purchase/' + payload + '/edit';
            axios.get(purchaseEditUrl, Vue.auth.getHeader()).then(response => {
                commit('setPurchasesEditData', response.data.purchase);
            });
        }
    }
};
