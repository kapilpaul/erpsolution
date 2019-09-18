export const accountStore = {
    state : {
        accounts : [],
        accountEditData : []
    },
    getters : {
        accounts : state => {
            return state.accounts;
        },
        accountEditData : state => {
            return state.accountEditData;
        }
    },
    mutations : {
        setAccounts : (state, payload) => {
            state.accounts = payload;
        },
        setAccountEditData : (state, payload) => {
            state.accountEditData = payload;
        }
    },
    actions : {
        setAccounts : ({ commit }, payload) => {
            axios.get(process.env.MIX_APP_URL + 'accounts', Vue.auth.getHeader()).then(response => {
                commit('setAccounts', response.data.accounts);
            });
        },
        setAccountEditData : ({ commit }, payload) => {
            commit('setAccountEditData', payload);
        }
    }
};
