export const bankStore = {
    state : {
        banks : [],
        bankEditData : [],
        bankTransactions : [],
        bankTransactionEditData : []
    },
    getters : {
        banks : state => {
            return state.banks;
        },
        bankEditData : state => {
            return state.bankEditData;
        },
        bankTransactions : state => {
            return state.bankTransactions;
        },
        bankTransactionEditData : state => {
            return state.bankTransactionEditData;
        }
    },
    mutations : {
        setBanks : (state, payload) => {
            state.banks = payload;
        },
        setBankEditData : (state, payload) => {
            state.bankEditData = payload;
        },
        setBankTransactions : (state, payload) => {
            state.bankTransactions = payload;
        },
        setBankTransactionEditData : (state, payload) => {
            state.bankTransactionEditData = payload;
        }
    },
    actions : {
        setBanks : ({ commit }, payload) => {
            axios.get(process.env.MIX_APP_URL + 'banks', Vue.auth.getHeader()).then(response => {
                commit('setBanks', response.data.banks);
            });
        },
        setBankEditData : ({ commit }, payload) => {
            commit('setBankEditData', payload);
        },
        setBankTransactions : ({ commit }, payload) => {
            var url = process.env.MIX_APP_URL + 'banks/';

            if(payload.code && payload.pagenum) {
                url = url + payload.code + '/show?page=' + payload.pagenum;
            } else if(payload.code) {
                url = url + payload.code + '/show';
            }  else {
                url = url + 'transactions';
            }

            axios.get(url, Vue.auth.getHeader()).then(response => {
                commit('setBankTransactions', response.data.bankTransactions);
            });
        },
        setBankTransactionEditData : ({ commit }, payload) => {
            commit('setBankTransactionEditData', payload);
        }
    }
};
