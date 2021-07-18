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
let editEmployee  = require('./components/employee/edit').default;

//Suppliers
let suppliers     = require('./components/supplier/index').default;
let storeSupplier = require('./components/supplier/create').default;
let editSupplier  = require('./components/supplier/edit').default;

//Categories
let categories    = require('./components/category/index').default;
let storeCategory = require('./components/category/create').default;
let editCategory  = require('./components/category/edit').default;

//Products
let products     = require('./components/product/index').default;
let storeProduct = require('./components/product/create').default;
let editProduct  = require('./components/product/edit').default;
let stock        = require('./components/product/stock').default;
let editStock    = require('./components/product/stockEdit').default;

//Expenses
let expenses     = require('./components/expense/index').default;
let storeExpense = require('./components/expense/create').default;
let editExpense  = require('./components/expense/edit').default;

//Salaries
let salaries      = require('./components/salary/index').default;
let givenSalaries = require('./components/salary/employees').default;
let paySalary     = require('./components/salary/create').default;
let viewSalaries  = require('./components/salary/view').default;
let editSalary    = require('./components/salary/edit').default;

//Customers
let customers     = require('./components/customer/index').default;
let storeCustomer = require('./components/customer/create').default;
let editCustomer  = require('./components/customer/edit').default;

// POS Component
let pos = require('./components/pos/pointOfSale').default;

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
    {path: '/stock', component: stock, name: 'stock'},
    {path: '/edit-stock/:id', component: editStock, name:'edit-stock'},

    //Expenses
    {path: '/expenses', component: expenses, name: 'expenses'},
    {path: '/store-expense', component: storeExpense, name: 'store-expense'},
    {path: '/edit-expense/:id', component: editExpense, name:'edit-expense'},

    //Salaries
    {path: '/salaries', component: salaries, name: 'salaries'},
    {path: '/given-salaries', component: givenSalaries, name: 'given-salaries'},
    {path: '/pay-salary/:id', component: paySalary, name:'pay-salary'},
    {path: '/view-salaries/:id', component: viewSalaries, name:'view-salaries'},
    {path: '/edit-salary/:id', component: editSalary, name:'edit-salary'},

    //Customers
    {path: '/customers', component: customers, name: 'customers'},
    {path: '/store-customer', component: storeCustomer, name: 'store-customer'},
    {path: '/edit-customer/:id', component: editCustomer, name:'edit-customer'},

    //POS
    { path: '/pos', component: pos, name:'pos'},

];
