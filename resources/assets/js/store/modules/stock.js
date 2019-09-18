import { store } from '../index'

export const stockStore = {
    state : {
        stock : [],
        inStock : [],
        outOfStock : [],
    },
    getters : {
        stock : state => {
            return state.stock;
        },
        inStock : state => {
            return state.inStock;
        },
        outOfStock : state => {
            return state.outOfStock;
        }
    },
    mutations : {
        setStocks : (state, payload) => {
            state.stock = payload;
        },
        setInStocks : (state, payload) => {
            state.inStock = payload;
        },
        setOutOfStock : (state, payload) => {
            state.outOfStock = payload;
        }
    },
    actions : {
        setStocks : ({ commit }, payload) => {
            store.dispatch('fetchData', {
                payload : payload,
                urlparam : 'stock',
                setLocation : 'setInStocks'
            });
        },
        setInStocks : ({ commit }, payload) => {
            store.dispatch('fetchData', {
                payload : payload,
                urlparam : 'instock',
                setLocation : 'setInStocks'
            });
        },
        setOutOfStock : ({ commit }, payload) => {
            store.dispatch('fetchData', {
                payload : payload,
                urlparam : 'outofstock',
                setLocation : 'setOutOfStock'
            });
        },
        fetchData ({ commit }, payload) {
            var StockUrl = process.env.MIX_APP_URL + 'reports/' + payload.urlparam;

            if(payload.payload == '') {
                StockUrl = StockUrl;
            } else if(payload.payload.search) {
                StockUrl = StockUrl + '/search/' + payload.payload.search;
            } else {
                StockUrl = StockUrl + "?page=" + payload.payload;
            }

            axios.get(StockUrl, Vue.auth.getHeader()).then(response => {
                commit(payload.setLocation, response.data.stock);
            });
        }
    }
};
