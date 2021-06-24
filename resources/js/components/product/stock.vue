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
                        <h6 class="m-0 font-weight-bold text-primary">Stock</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center pr-2 pl-2">Image</th>
                                <th class="text-center pr-1 pl-1">Name</th>
                                <th class="text-center pr-1 pl-1">Code</th>
                                <th class="text-center pr-1 pl-1">Category</th>
                                <th class="text-center pr-1 pl-1">Buying Price</th>
                                <th class="text-center pr-1 pl-1">Status</th>
                                <th class="text-center pr-1 pl-1">Quantity</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="product in filterSearch" :key="product.id">
                                <td class="text-center pr-2 pl-2"><img :src="product.image" class="rounded product_photo"></td>
                                <td class="text-center pr-1 pl-1">{{ product.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.code }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.category.name }}</td>
                                <td class="text-center pr-1 pl-1">{{ product.buying_price }}</td>
                                <td class="text-center pr-1 pl-1" v-if="product.quantity >= 1"><span class="badge badge-success">Available</span></td>
                                <td class="text-center pr-1 pl-1" v-else><span class="badge badge-danger">Stock Out</span></td>
                                <td class="text-center pr-1 pl-1">{{ product.quantity }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-stock', params:{id:product.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
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
        name: "stock",
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
