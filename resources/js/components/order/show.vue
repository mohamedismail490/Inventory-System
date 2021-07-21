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
                                        <h1 class="h4 text-gray-900 mb-4"> Order #{{order.id}} </h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <div class="card">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><b>Customer Name :</b> {{ order.customer_name }}</li>
                                                    <li class="list-group-item"><b>Mobile :</b> {{ order.customer.mobile }}</li>
                                                    <li class="list-group-item"><b>Address :</b> {{ order.customer.address }}</li>
                                                    <li class="list-group-item"><b>Date :</b> {{ order.order_date }}</li>
                                                    <li class="list-group-item"><b>Pay Through :</b> {{ order.pay_by }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <div class="card">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><b>Sub Total :</b> {{ order.sub_total }} $</li>
                                                    <li class="list-group-item"><b>Vat :</b> {{ order.vat_value }} $&nbsp;&nbsp;(&nbsp;<span style="color: #fc544b;">{{ order.vat }}%</span>&nbsp;)</li>
                                                    <li class="list-group-item"><b>Total :</b> {{ order.total }} $</li>
                                                    <li class="list-group-item"><b>Pay Amount :</b> {{ order.pay }} $</li>
                                                    <li class="list-group-item"><b>Due Amount :</b> {{ order.due }} $</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 mb-4">
                                            <!-- Simple Tables -->
                                            <div class="card">
                                                <div
                                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                    <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table align-items-center table-flush">
                                                        <thead class="thead-light">
                                                        <tr>
                                                            <th>Image</th>
                                                            <th>Product</th>
                                                            <th>Code</th>
                                                            <th>Qty</th>
                                                            <th>Unit Price</th>
                                                            <th>Total</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="detail in order.order_details">
                                                            <td><img :src="detail.product.image" class="pro_photo rounded"></td>
                                                            <td>{{ detail.product.name }}</td>
                                                            <td>{{ detail.product.code }}</td>
                                                            <td>{{ detail.quantity }}</td>
                                                            <td>{{ detail.price }} $</td>
                                                            <td>{{ detail.sub_total }} $</td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="card-footer"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                    </div>
                                    <div class="text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: "show",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            }else {
                let id = this.$route.params.id;
                axios.get('/api/orders/'+id)
                    .then(({data}) => {
                        this.order = data;
                    })
                    .catch(err => {
                        if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status){
                            Notification.customError(err.response.data.message);
                        }else {
                            Notification.error();
                        }
                    })
            }
        },
        data() {
            return {
                errors: {},
                order: {},
            }
        }
    }
</script>
<style type="text/css" scoped>
    .pro_photo {
        height: 40px;
        width: 40px;
    }
</style>
