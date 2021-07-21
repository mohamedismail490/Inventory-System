<template>
    <div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">POS Dashboard</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link to="/home">Home</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">POS</li>
            </ol>
        </div>
        <div class="row mb-3">
            <div class="col-xl-5 col-lg-5">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Expense Insert</h6>
                        <a class="btn btn-sm btn-info" style="color: #FFFFFF;">Add Customer</a>
                    </div>
                    <div class="table-responsive" style="font-size: 12px;">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="pl-2 pr-1">Name</th>
                                <th class="pl-1 pr-1 text-center" width="25%">Qty</th>
                                <th class="pl-1 pr-1 text-center">Unit</th>
                                <th class="pl-1 pr-1 text-center">Total</th>
                                <th class="pl-1 pr-1 text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="carts.length > 0">

                            <tr v-for="cart in carts" :key="cart.id">
                                <td class="pl-2 pr-1">{{ cart.name }}</td>
                                <td class="pl-1 pr-1 text-center">
                                    (&nbsp;<span style="font-weight: bold; font-size: medium;">{{cart.quantity}}</span>&nbsp;)&nbsp;
                                    <button @click.prevent="increment(cart.id)" class="btn btn-sm btn-success btnPadding">+
                                    </button>
                                    <button @click.prevent="decrement(cart.id)" class="btn btn-sm btn-danger btnPadding"
                                            v-if="cart.quantity >= 2">-
                                    </button>
                                    <button class="btn btn-sm btn-danger btnPadding" v-else="" disabled="disabled">-</button>
                                </td>
                                <td class="pl-1 pr-1 text-center">{{ cart.price }}</td>
                                <td class="pl-1 pr-1 text-center">{{ cart.sub_total }}</td>
                                <td class="pl-1 pr-1 text-center">
                                    <a @click="removeItem(cart.id)" class="btn btn-sm btn-primary"
                                       style="color: #FFFFFF;">X</a>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5" class="text-center" style="font-weight: bold; color: #fc544b;">Cart is Empty !!!</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="card-footer">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Total
                                Quantity:
                                <strong>{{ qty }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Sub Total:
                                <strong>{{ subTotal }} $</strong>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">Vat:
                                <strong>{{ vatValue.vat }} %</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Total :
                                <strong>{{ total }} $</strong>
                            </li>

                        </ul>
                        <br>

                        <form @submit.prevent="createOrder">
                            <label for="customer_id">Customer Name</label>
                            <select class="form-control" id="customer_id" v-model="customer_id">
                                <option :value="customer.id" v-for="customer in customers">{{customer.name }}
                                </option>
                            </select>
                            <small class="text-danger" v-if="errors.customer_id">{{ errors.customer_id[0] }}<br>
                            </small>
                            <label for="pay">Pay</label>
                            <input type="text" id="pay" class="form-control" v-model="pay">
                            <small class="text-danger" v-if="errors.pay">{{ errors.pay[0] }}<br>
                            </small>
                            <label for="due">Due</label>
                            <input type="text" id="due" class="form-control" v-model="due">
                            <small class="text-danger" v-if="errors.due">{{ errors.due[0] }}<br>
                            </small>
                            <label for="payBy">Pay By</label>
                            <select id="payBy" class="form-control" v-model="payBy">
                                <option value="HandCash">Hand Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="GiftCard">Gift Card</option>
                            </select>
                            <small class="text-danger" v-if="errors.pay_by">{{ errors.pay_by[0] }}<br>
                            </small>
                            <br>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Products Sold</h6>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="true">All Products </a>
                        </li>
                        <li class="nav-item" v-for="category in categories" :key="category.id">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="false" @click="categoryProducts(category.id)">{{
                                category.name }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card-body">
                                <input type="text" v-model="searchTerm" class="form-control mb-2"
                                       style="width: 100%;"
                                       placeholder="Search Products">
                                <div class="row" v-if="filterSearch.length > 0">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-6" v-for="product in filterSearch"
                                         :key="product.id">
                                        <div class="card" style="margin-bottom: 5px; width: 100%; height: 95%;">
                                            <a class="btn btn-sm" @click.prevent="addToCart(product.id)">
                                                <img :src="product.image"
                                                     class="card-img-top pos_pro_photo img-fluid img-thumbnail">
                                                <div class="card-body">
                                                    <h6 class="card-title" style="width: 100%">{{ product.name }}</h6>
                                                    <div class="text-center" style="width: 100%">
                                                        <small class="badge badge-success"
                                                               v-if="product.quantity  >= 1 ">Available {{
                                                            product.quantity }}
                                                        </small>
                                                        <small class="badge badge-danger" v-else=" ">Stock Out</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <br>
                                    <h5 class="text-center" style="font-weight: bold; color: #fc544b;">There are no Products !!!</h5>
                                </div>
                            </div>


                        </div>  <!--  End TBAS HOME -->


                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                            <div class="card-body">
                                <input type="text" v-model="categorySearchTerm" class="form-control mb-2"
                                       style="width: 100%;"
                                       placeholder="Search Products">

                                <div class="row" v-if="categoryFilterSearch.length > 0">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-6"
                                         v-for="catProduct in categoryFilterSearch"
                                         :key="catProduct.id">
                                        <div class="card" style="margin-bottom: 5px; width: 100%; height: 95%;">
                                            <a class="btn btn-sm" @click.prevent="addToCart(catProduct.id)">
                                                <img :src="catProduct.image"
                                                     class="card-img-top pos_pro_photo img-thumbnail">
                                                <div class="card-body">
                                                    <h6 class="card-title" style="width: 100%">{{ catProduct.name
                                                        }}</h6>
                                                    <div class="text-center" style="width: 100%">
                                                        <small class="badge badge-success"
                                                               v-if="catProduct.quantity  >= 1 ">Available {{
                                                            catProduct.quantity }}
                                                        </small>
                                                        <small class="badge badge-danger" v-else=" ">Stock Out</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <br>
                                    <h5 class="text-center" style="font-weight: bold; color: #fc544b;">There are no Products !!!</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Category Wise Product -->
                </div>
            </div>
        </div>
    </div>
</template>


<script type="text/javascript">

    export default {
        name: 'pointOfSale',
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            }
        },
        data() {
            return {
                customer_id: '',
                pay: '',
                due: '',
                payBy: '',
                products: [],
                categories: '',
                catProducts: [],
                searchTerm: '',
                categorySearchTerm: '',
                customers: '',
                carts: [],
                vatValue: '',
                errors: {},
            }
        },
        computed: {
            filterSearch() {
                return this.products.filter(product => {
                    return product.name.match(this.searchTerm)
                })
            },
            categoryFilterSearch() {
                return this.catProducts.filter(categoryProduct => {
                    return categoryProduct.name.match(this.categorySearchTerm)
                })
            },
            qty() {
                let sum = 0;
                for (let i = 0; i < this.carts.length; i++) {
                    sum += (parseInt(this.carts[i].quantity));
                }
                return sum;
            },
            subTotal() {
                let sum = 0;
                for (let i = 0; i < this.carts.length; i++) {
                    sum += (parseFloat(this.carts[i].quantity) * parseFloat(this.carts[i].price));
                }
                return Math.fround(sum).toFixed(2);
            },
            total() {
                let vat = parseFloat(this.subTotal * (this.vatValue.vat / 100));
                let sub_total = parseFloat(this.subTotal);
                return Math.fround(vat + sub_total).toFixed(2);
            }
        },

        methods: {
            // Cart Methods Here
            addToCart(id) {
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/pos/cart/add/' + id)
                        .then(res => {
                            if (res.data.status) {
                                Reload.$emit('AfterAdd');
                                Notification.customSuccess(res.data.message);
                            }else {
                                Notification.customError(res.data.message);
                            }
                        })
                        .catch(err => {
                            if (typeof (err.response.data.errors) != 'undefined') {
                                this.errors = err.response.data.errors
                            } else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                                Notification.customError(err.response.data.message);
                            } else {
                                Notification.error();
                            }
                        })
                }
            },
            cartProducts() {
                axios.get('/api/pos')
                    .then(({data}) => (this.carts = data))
                    .catch()
            },
            removeItem(id) {
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/pos/cart/remove/' + id)
                        .then(res => {
                            if (res.data.status) {
                                Reload.$emit('AfterAdd');
                                Notification.customSuccess(res.data.message);
                            }else {
                                Notification.customError(res.data.message);
                            }
                        })
                        .catch(err => {
                            if (typeof (err.response.data.errors) != 'undefined') {
                                this.errors = err.response.data.errors
                            } else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                                Notification.customError(err.response.data.message);
                            } else {
                                Notification.error();
                            }
                        })
                }
            },
            increment(id) {
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/pos/cart/increment/' + id)
                        .then(res => {
                            if (res.data.status) {
                                Reload.$emit('AfterAdd');
                                Notification.customSuccess(res.data.message);
                            }else {
                                Notification.customError(res.data.message);
                            }
                        })
                        .catch(err => {
                            if (typeof (err.response.data.errors) != 'undefined') {
                                this.errors = err.response.data.errors
                            } else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                                Notification.customError(err.response.data.message);
                            } else {
                                Notification.error();
                            }
                        })
                }
            },
            decrement(id) {
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/pos/cart/decrement/' + id)
                        .then(res => {
                            Reload.$emit('AfterAdd');
                            if (res.data.status) {
                                Notification.customSuccess(res.data.message);
                            }else {
                                Notification.customError(res.data.message);
                            }
                        })
                        .catch(err => {
                            if (typeof (err.response.data.errors) != 'undefined') {
                                this.errors = err.response.data.errors
                            } else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                                Notification.customError(err.response.data.message);
                            } else {
                                Notification.error();
                            }
                        })
                }
            },
            vat() {
                axios.get('/api/settings/vat')
                    .then(({data}) => (this.vatValue = data))
                    .catch()
            },
            createOrder() {
                var data = {
                    customer_id: this.customer_id,
                    pay_by: this.payBy,
                    pay: this.pay,
                    due: this.due,
                };
                axios.post('/api/orders/store', data)
                    .then(res => {
                        Reload.$emit('AfterAdd');
                        if (res.data.status) {
                            Notification.customSuccess(res.data.message);
                            this.$router.push({name: 'home'})
                        }else {
                            Notification.customError(res.data.message);
                        }
                    })
                    .catch(err => {
                        if (typeof (err.response.data.errors) != 'undefined') {
                            this.errors = err.response.data.errors
                        } else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                            Notification.customError(err.response.data.message);
                        } else {
                            Notification.error();
                        }
                    })
            },
            // End Cart Methods
            allProducts() {
                axios.get('/api/products')
                    .then(({data}) => (this.products = data))
                    .catch()
            },
            allCategories() {
                axios.get('/api/categories')
                    .then(({data}) => (this.categories = data))
                    .catch()
            },
            allCustomers() {
                axios.get('/api/customers')
                    .then(({data}) => (this.customers = data))
                    .catch()
            },
            categoryProducts(id) {
                axios.get('/api/categories/' + id + '/products')
                    .then(({data}) => (this.catProducts = data))
                    .catch()
            }
        },
        mounted() {
            this.allProducts();
            this.allCategories();
            this.allCustomers();
            this.cartProducts();
            this.vat();
            Reload.$on('AfterAdd', () => {
                this.cartProducts();
            })

        },

    }

</script>


<style type="text/css" scoped>
    .pos_pro_photo {
        height: 150px;
        width: 100%;
    }
    .btnPadding {
        padding-right: 5px;
        padding-left: 5px;
        padding-top: 1px;
        padding-bottom: 1px;
    }
</style>
