<template>
    <div>
        <br><br>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <input type="text" v-model="searchTerm" class="form-control" placeholder="Search Here">
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
                            <tbody v-if="filterSearch.length > 0">
                                <tr v-for="order in filterSearch" :key="order.id">
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
        <!--Row-->
    </div>
</template>


<script type="text/javascript">
    export default {
        name: "index",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            }
        },
        data() {
            return {
                orders: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.orders.filter(order => {
                    return order.customer_name.match(this.searchTerm)
                        || order.pay_by.match(this.searchTerm)
                        || order.order_date.match(this.searchTerm)
                })
            }
        },
        methods: {
            allOrders() {
                axios.get('/api/orders')
                    .then(({data}) => {
                        this.orders = data;
                    })
                    .catch()
            }
        },
        mounted() {
            this.allOrders();
        }
    }
</script>

<style scoped>
</style>
