//Auth
let login    = require('./components/auth/login').default;
let register = require('./components/auth/register').default;
let forget   = require('./components/auth/forget').default;
let logout   = require('./components/auth/logout').default;

//Home
let home = require('./components/home').default;

//Employees
let employees     = require('./components/employee/index').default;
let storeEmployee = require('./components/employee/create').default;
let editEmployee  = require('./components/employee/edit.vue').default;

//Suppliers
let suppliers     = require('./components/supplier/index').default;
let storeSupplier = require('./components/supplier/create').default;
let editSupplier  = require('./components/supplier/edit.vue').default;

//Categories
let categories    = require('./components/category/index').default;
let storeCategory = require('./components/category/create').default;
let editCategory  = require('./components/category/edit.vue').default;

//Products
let products     = require('./components/product/index').default;
let storeProduct = require('./components/product/create').default;
let editProduct  = require('./components/product/edit.vue').default;


export const routes = [
    {path: '/', component: login, name: '/'},
    {path: '/register', component: register, name: 'register'},
    {path: '/forget', component: forget, name: 'forget'},
    {path: '/logout', component: logout, name: 'logout'},
    {path: '/home', component: home, name: 'home'},

    //Employees
    {path: '/employees', component: employees, name: 'employees'},
    {path: '/store-employee', component: storeEmployee, name: 'store-employee'},
    {path: '/edit-employee/:id', component: editEmployee, name:'edit-employee'},

    //Suppliers
    {path: '/suppliers', component: suppliers, name: 'suppliers'},
    {path: '/store-supplier', component: storeSupplier, name: 'store-supplier'},
    {path: '/edit-supplier/:id', component: editSupplier, name:'edit-supplier'},

    //Categories
    {path: '/categories', component: categories, name: 'categories'},
    {path: '/store-category', component: storeCategory, name: 'store-category'},
    {path: '/edit-category/:id', component: editCategory, name:'edit-category'},

    //Products
    {path: '/products', component: products, name: 'products'},
    {path: '/store-product', component: storeProduct, name: 'store-product'},
    {path: '/edit-product/:id', component: editProduct, name:'edit-product'},

];
