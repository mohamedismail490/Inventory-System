<template>
    <div>
        <div class="row">
            <router-link to="/store-product" class="btn btn-primary">Add Product</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
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
                                <th class="text-center pr-1 pl-1">Quantity</th>
                                <th class="text-center pr-1 pl-1">Buying Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in filterSearch" :key="product.id">
                                <td class="text-center pr-2 pl-2"><img :src="product.image" class="rounded product_photo"></td>
                                <td class="text-center pr-1 pl-1">{{ product.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.code }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.root }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.category.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.supplier.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.buying_price }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.selling_price }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.quantity }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.buy_date }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-product', params:{id:product.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
                                        <a @click="deleteProduct(product.id)" class="btn btn-sm btn-danger" style="color: #ffffff;">Delete</a>
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
                products: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.products.filter(product => {
                    return product.name.match(this.searchTerm)
                        || product.code.match(this.searchTerm)
                        || product.category.name.match(this.searchTerm)
                        || product.supplier.name.match(this.searchTerm)
                        || product.buy_date.match(this.searchTerm)
                })
            }
        },

        methods: {
            allProducts() {
                axios.get('/api/products')
                    .then(({data}) => {
                        this.products = data;
                    })
                    .catch()
            },
            deleteProduct(id) {
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
                            axios.delete('/api/products/' + id)
                                .then(res => {
                                    console.log(res.data);
                                    if (res.data.status) {
                                        this.products = this.products.filter(product => {
                                            return product.id != id
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
                                    this.$router.push({name: 'products'});
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
            this.allProducts();
        }
    }
</script>

<style scoped>
    .product_photo {
        height: 40px;
        width: 40px;
    }
</style>
