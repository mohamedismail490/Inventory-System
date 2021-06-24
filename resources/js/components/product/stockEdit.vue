<template>
    <div>
        <div class="row">
            <router-link to="/stock" class="btn btn-primary">Go Back</router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Edit Stock</h1>
                                        <h5>{{ form.name }}</h5>
                                    </div>
                                    <form class="user" @submit.prevent="productStockUpdate">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="quantity">Product Stock</label>
                                                    <input type="text" class="form-control" id="quantity"
                                                           placeholder="Enter Product Stock" v-model="form.quantity">
                                                    <small class="text-danger" v-if="errors.quantity">{{
                                                        errors.quantity[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                                        </div>
                                    </form>
                                    <hr>
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

<script>
    export default {
        name: "stockEdit",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            } else {
                let id = this.$route.params.id;
                axios.get('/api/products/' + id)
                    .then(({data}) => {
                        this.form = data;
                    })
                    .catch(err => {
                        if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                            Notification.customError(err.response.data.message);
                        } else {
                            Notification.error();
                        }
                    })
            }
        },
        data() {
            return {
                form: {
                    name: '',
                    quantity: '',
                },
                errors: {}
            }
        },
        methods: {
            productStockUpdate() {
                let id = this.$route.params.id;
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/products/' + id + '/stock/update', this.form)
                        .then(res => {
                            if (res.data.status) {
                                this.$router.push({name: 'stock'});
                                Notification.customSuccess(res.data.message);
                            } else {
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
        }
    }
</script>

<style scoped>

</style>
