
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import axios from 'axios';
import swal from 'sweetalert2';
import auth from './auth.js';
import { store } from './store/index'

// components
import categoryCreate from './components/settings/category/create.vue';
import categoryIndex from './components/settings/category/index.vue';
import accountCreate from './components/settings/accounts/account/create.vue';
import accountIndex from './components/settings/accounts/account/index.vue';
import supplierCreate from './components/settings/suppliers/create.vue';
import supplierIndex from './components/settings/suppliers/index.vue';
import productCreate from './components/settings/products/create.vue';
import productIndex from './components/settings/products/index.vue';
import purchaseIndex from './components/purchase/index.vue';
import purchaseCreate from './components/purchase/create.vue';
import purchaseEdit from './components/purchase/edit.vue';
import invoiceIndex from './components/sells/invoice/index.vue';
import invoiceCreate from './components/sells/invoice/create.vue';
import invoiceEdit from './components/sells/invoice/edit.vue';
import customerCreate from './components/customer/create.vue';
import customerIndex from './components/customer/index.vue';
import productInStock from './components/reports/stock/instock.vue';
import productOutOfStock from './components/reports/stock/outofstock.vue';
import productStockIndex from './components/reports/stock/index.vue';
import bankCreate from './components/bank/default/create.vue';
import bankIndex from './components/bank/default/index.vue';
import bankTransactionCreate from './components/bank/transaction/create.vue';
import bankTransactionShow from './components/bank/transaction/show.vue';
import transactionCreate from './components/settings/accounts/transaction/create.vue';
import transactionShow from './components/settings/accounts/transaction/show.vue';

console.log('%c Developed By Kapil Paul', 'background-color:#333;padding:20px 40px;border-radius:5px;color:#fff;');

Vue.use(auth);

axios.defaults.baseURL = process.env.MIX_APP_URL;

Object.defineProperties(Vue.prototype, {
    $siteurl : {
        value: process.env.MIX_APP_URL
    },
    $swal : {
        value: swal
    },
    $axios : {
        value: axios
    }
});

const app = new Vue({
    el: '#app',
    store,
    components : {
        categoryCreate,
        categoryIndex,
        accountCreate,
        accountIndex,
        supplierIndex,
        supplierCreate,
        productIndex,
        productCreate,
        purchaseIndex,
        purchaseCreate,
        purchaseEdit,
        invoiceIndex,
        invoiceCreate,
        invoiceEdit,
        customerCreate,
        customerIndex,
        productInStock,
        productOutOfStock,
        productStockIndex,
        bankCreate,
        bankIndex,
        bankTransactionCreate,
        bankTransactionShow,
        transactionCreate,
        transactionShow
    }
});
