<template>
    <div>
        <div class="row">
            <router-link to="/orders" class="btn btn-primary">All Orders</router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Search Orders</h1>
                                    </div>
                                    <form class="user" @submit.prevent="searchDate">
                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <label for="date"><b>Search By Date </b></label>
                                                    <input type="date" class="form-control" id="date"
                                                           v-model="date">
                                                    <small class="text-danger" v-if="errors.date">{{ errors.date[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Search</button>
                                        </div>
                                    </form>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <!-- Simple Tables -->
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
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
                                        <th class="text-center pl-1 pr-1">Order Date</th>
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
                                        <td class="text-center pl-1 pr-1">{{ order.order_date }}</td>
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
                                        <td colspan="11" class="text-center" style="font-weight: bold; color: #fc544b;">No Orders Available !!!</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: "search",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            }
        },
        data() {
            return {
                date: '',
                orders: {},
                errors: {}
            }
        },
        methods: {
            searchDate() {
                var formData = {date: this.date};
                axios.post('/api/orders/search', formData)
                    .then(({data}) => (this.orders = data))
                    .catch(error => this.errors = error.response.data.errors)
            },
        }
    }
</script>
<style type="text/css">
</style>
