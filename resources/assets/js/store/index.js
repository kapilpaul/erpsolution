import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import auth from '../auth.js';
import { purchaseStore } from './modules/purchase';
import { customerStore } from './modules/customer';
import { stockStore } from './modules/stock';
import { bankStore } from './modules/bank';
import { invoiceStore } from './modules/invoice';
import { accountStore } from './modules/account';

Vue.use(Vuex);
Vue.use(auth);

// const purchaseStore = purchaseStore;

export const store = new Vuex.Store({
    state : {
        categories : [],
        validationErrors : [],
        categoryEditData : [],
        suppliers : [],
        suppliersEditData : [],
        products : [],
        productsEditData : [],
        submitted : false
    },
    getters : {
        validationErrors : state => {
            return state.validationErrors;
        },
        categories : state => {
            return state.categories;
        },
        categoryEditData : state => {
            return state.categoryEditData;
        },
        suppliers : state => {
            return state.suppliers;
        },
        suppliersEditData : state => {
            return state.suppliersEditData;
        },
        products : state => {
            return state.products;
        },
        productsEditData : state => {
            return state.productsEditData;
        },
        submitted : state => {
            return state.submitted;
        }
    },
    mutations : {
        setValidationErrors : (state, payload) => {
            state.validationErrors = payload;
        },
        setCategories : (state, payload) => {
            state.categories = payload;
        },
        setCategoryEditData : (state, payload) => {
            state.categoryEditData = payload;
        },
        setSuppliers : (state, payload) => {
            state.suppliers = payload;
        },
        setSuppliersEditData : (state, payload) => {
            state.suppliersEditData = payload;
        },
        setProducts : (state, payload) => {
            state.products = payload;
        },
        setProductsEditData : (state, payload) => {
            state.productsEditData = payload;
        },
        setSubmitted : (state, payload) => {
            state.submitted = payload;
        }
    },
    actions : {
        setValidationErrors : ({ commit }, payload) => {
            commit('setValidationErrors', payload);
        },
        setCategories : ({ commit }, payload) => {
            axios.get(process.env.MIX_APP_URL + 'api/category', Vue.auth.getHeader()).then(response => {
                commit('setCategories', response.data.categories);
            });
        },
        setCategoryEditData : ({ commit }, payload) => {
            commit('setCategoryEditData', payload);
        },
        setSuppliers : ({ commit }, payload) => {
            axios.get(process.env.MIX_APP_URL + 'api/suppliers', Vue.auth.getHeader()).then(response => {
                commit('setSuppliers', response.data.suppliers);
            });
        },
        setSuppliersEditData : ({ commit }, payload) => {
            commit('setSuppliersEditData', payload);
        },
        setProducts : ({ commit }, payload) => {
            var productUrl = process.env.MIX_APP_URL + 'api/products';
            if(payload == '') {
                productUrl = productUrl;
            } else if(payload.search) {
                productUrl = productUrl + '/search/' + payload.search;
            } else {
                productUrl = productUrl + "?page=" + payload;
            }

            axios.get(productUrl, Vue.auth.getHeader()).then(response => {
                commit('setProducts', response.data.products);
            });

        },
        setProductsEditData : ({ commit }, payload) => {
            commit('setProductsEditData', payload);
        },
        setSubmitted : ({ commit }, payload) => {
            commit('setSubmitted', payload);
        }
    },
    modules : {
        purchaseStore,
        customerStore,
        stockStore,
        bankStore,
        invoiceStore,
        accountStore
    }
});