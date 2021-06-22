<template>
    <div>
        <div class="row">
            <router-link to="/store-supplier" class="btn btn-primary">Add Supplier</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Suppliers List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Shop Name</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="supplier in filterSearch" :key="supplier.id">
                                <td><img :src="supplier.photo" class="rounded supplier_photo"></td>
                                <td>{{ supplier.name }}</td>
                                <td>{{ supplier.email }}</td>
                                <td>{{ supplier.mobile }}</td>
                                <td>{{ supplier.shop_name }}</td>
                                <td>{{ supplier.created_at }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-supplier', params:{id:supplier.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
                                        <a @click="deleteSupplier(supplier.id)" class="btn btn-sm btn-danger" style="color: #ffffff;">Delete</a>
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
                suppliers: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.suppliers.filter(supplier => {
                    return supplier.name.match(this.searchTerm)
                        || supplier.email.match(this.searchTerm)
                        || supplier.mobile.match(this.searchTerm)
                        || supplier.shop_name.match(this.searchTerm)
                        || supplier.created_at.match(this.searchTerm)
                })
            }
        },

        methods: {
            allSuppliers() {
                axios.get('/api/suppliers')
                    .then(({data}) => {
                        this.suppliers = data;
                    })
                    .catch()
            },
            deleteSupplier(id) {
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
                            axios.delete('/api/suppliers/' + id)
                                .then(res => {
                                    console.log(res.data);
                                    if (res.data.status) {
                                        this.suppliers = this.suppliers.filter(supplier => {
                                            return supplier.id != id
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
                                    this.$router.push({name: 'suppliers'});
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
            this.allSuppliers();
        }
    }
</script>

<style scoped>
    .supplier_photo {
        height: 40px;
        width: 40px;
    }
</style>
