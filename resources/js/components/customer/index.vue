<template>
    <div>
        <div class="row">
            <router-link to="/store-customer" class="btn btn-primary">Add Customer</router-link>
        </div>
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
                        <h6 class="m-0 font-weight-bold text-primary">Customers List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="customer in filterSearch" :key="customer.id">
                                <td><img :src="customer.photo" class="rounded customer_photo"></td>
                                <td>{{ customer.name }}</td>
                                <td>{{ customer.email }}</td>
                                <td>{{ customer.mobile }}</td>
                                <td>{{ customer.created_at }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-customer', params:{id:customer.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
                                        <a @click="deleteCustomer(customer.id)" class="btn btn-sm btn-danger" style="color: #ffffff;">Delete</a>
                                    </div>
                                </td>
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
                customers: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.customers.filter(customer => {
                    return customer.name.match(this.searchTerm)
                        || customer.email.match(this.searchTerm)
                        || customer.mobile.match(this.searchTerm)
                        || customer.created_at.match(this.searchTerm)
                })
            }
        },
        methods: {
            allCustomers() {
                axios.get('/api/customers')
                    .then(({data}) => {
                        this.customers = data;
                    })
                    .catch()
            },
            deleteCustomer(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.value) {
                        if (!User.loggedIn()){
                            this.$router.push({ name: '/'})
                        }else {
                            axios.delete('/api/customers/' + id)
                                .then(res => {
                                    if (res.data.status) {
                                        this.customers = this.customers.filter(customer => {
                                            return customer.id != id
                                        });
                                        Swal.fire(
                                            'Deleted!',
                                            res.data.message,
                                            'success'
                                        )
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            res.data.message,
                                            'error'
                                        )
                                    }
                                })
                                .catch(() => {
                                    this.$router.push({name: 'customers'});
                                    Swal.fire(
                                        'Error!',
                                        'Something wrong happened! Please, try again.',
                                        'error'
                                    )
                                });
                        }
                    }
                })
            }
        },
        mounted() {
            this.allCustomers();
        }
    }
</script>

<style scoped>
    .customer_photo {
        height: 40px;
        width: 40px;
    }
</style>
