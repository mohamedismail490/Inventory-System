<template>
    <div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>

        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Today Sells Amount</div>
                                <div class="h5 mb-0 mt-3 font-weight-bold text-gray-800">$ {{todaySell}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-primary mt-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Today Income</div>
                                <div class="h5 mb-0 mt-3 font-weight-bold text-gray-800">$ {{todayIncome}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-success mt-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Today Due</div>
                                <div class="h5 mb-0 mt-3 mr-3 font-weight-bold text-gray-800">$ {{todayDue}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-info mt-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Today Expense</div>
                                <div class="h5 mb-0 mt-3 font-weight-bold text-gray-800">$ {{todayExpense}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-warning mt-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Today Orders</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center pl-1 pr-1">Order Number</th>
                                <th class="text-center pl-1 pr-1">Customer Name</th>
                                <th class="text-center pl-1 pr-1">Sub Total</th>
                                <th class="text-center pl-1 pr-1">Vat</th>
                                <th class="text-center pl-1 pr-1">Vat Value</th>
                                <th class="text-center pl-1 pr-1">Total</th>
                                <th class="text-center pl-1 pr-1">Pay</th>
                                <th class="text-center pl-1 pr-1">Due</th>
                                <th class="text-center pl-1 pr-1">Pay By</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="orders.length > 0">
                            <tr v-for="order in orders" :key="order.id">
                                <td class="text-center pl-1 pr-1">#{{ order.id }}</td>
                                <td class="text-center pl-1 pr-1">{{ order.customer_name }}</td>
                                <td class="text-center pl-1 pr-1">{{ order.sub_total }} $</td>
                                <td class="text-center pl-1 pr-1">{{ order.vat }}%</td>
                                <td class="text-center pl-1 pr-1">{{ order.vat_value }} $</td>
                                <td class="text-center pl-1 pr-1">{{ order.total }} $</td>
                                <td class="text-center pl-1 pr-1">{{ order.pay }} $</td>
                                <td class="text-center pl-1 pr-1">{{ order.due }} $</td>
                                <td class="text-center pl-1 pr-1">{{ order.pay_by }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'view-order', params:{id:order.id}}"
                                                     class="btn btn-sm btn-primary">View
                                        </router-link>&nbsp;
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else>
                            <tr>
                                <td colspan="11" class="text-center" style="font-weight: bold; color: #fc544b;">No Orders Today !!!</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>

            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Stock Out Products</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center pr-2 pl-2">Image</th>
                                <th class="text-center pr-1 pl-1">Name</th>
                                <th class="text-center pr-1 pl-1">Code</th>
                                <th class="text-center pr-1 pl-1">Root</th>
                                <th class="text-center pr-1 pl-1">Category</th>
                                <th class="text-center pr-1 pl-1">Supplier</th>
                                <th class="text-center pr-1 pl-1">Buying Price</th>
                                <th class="text-center pr-1 pl-1">Selling Price</th>
                                <th class="text-center pr-1 pl-1">Buying Date</th>
                                <th class="text-center pr-1 pl-1">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="products.length > 0">
                            <tr v-for="product in products" :key="product.id">
                                <td class="text-center pr-2 pl-2"><img :src="product.image" class="rounded product_photo"></td>
                                <td class="text-center pr-1 pl-1">{{ product.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.code }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.root }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.category.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.supplier.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.buying_price }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.selling_price }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.buy_date }}</td>
                                <td class="text-center pr-1 pl-1" v-if="product.quantity >= 1"><span class="badge badge-success">Available</span></td>
                                <td class="text-center pr-1 pl-1" v-else><span class="badge badge-danger">Stock Out</span></td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-stock', params:{id:product.id}}"
                                                     class="btn btn-sm btn-primary">Edit Stock
                                        </router-link>&nbsp;
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else>
                            <tr>
                                <td colspan="12" class="text-center" style="font-weight: bold; color: #fc544b;">No Stock Out Products Found !!!</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "home",
        created() {
            if (!User.loggedIn()){
                this.$router.push({ name: '/'})
            }
        },
        data() {
            return {
                todaySell: '',
                todayIncome: '',
                todayDue: '',
                todayExpense: '',
                orders: [],
                products: []
            }
        },
        methods: {
            todaySells() {
                axios.get('/api/home/today/sell')
                    .then(({data}) => {
                        this.todaySell = data;
                    })
                    .catch()
            },
            todayIncomes() {
                axios.get('/api/home/today/income')
                    .then(({data}) => {
                        this.todayIncome = data;
                    })
                    .catch()
            },
            todayDues() {
                axios.get('/api/home/today/due')
                    .then(({data}) => {
                        this.todayDue = data;
                    })
                    .catch()
            },
            todayExpenses() {
                axios.get('/api/home/today/expense')
                    .then(({data}) => {
                        this.todayExpense = data;
                    })
                    .catch()
            },
            todayOrders() {
                axios.get('/api/home/orders/today')
                    .then(({data}) => {
                        this.orders = data;
                    })
                    .catch()
            },
            stockOutProducts() {
                axios.get('/api/home/products/stock_out')
                    .then(({data}) => {
                        this.products = data;
                    })
                    .catch()
            },
        },
        mounted() {
            this.todaySells();
            this.todayIncomes();
            this.todayDues();
            this.todayExpenses();
            this.todayOrders();
            this.stockOutProducts();
        }
    }
</script>

<style scoped>
    .product_photo {
        height: 40px;
        width: 40px;
    }
</style>
