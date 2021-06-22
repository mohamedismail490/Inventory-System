<template>
    <div>
        <div class="row">
            <router-link to="/products" class="btn btn-primary">All Products</router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Add Product</h1>
                                    </div>
                                    <form class="user" @submit.prevent="productInsert" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                           placeholder="Enter Product Name" v-model="form.name">
                                                    <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="code">Code</label>
                                                    <input type="text" class="form-control" id="code"
                                                           placeholder="Enter Product Code" v-model="form.code">
                                                    <small class="text-danger" v-if="errors.code">{{ errors.code[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="category_id">Category</label>
                                                    <select class="form-control" id="category_id"
                                                            v-model="form.category_id">
                                                        <option v-for="category in categories" :value="category.id">
                                                            {{category.name}}
                                                        </option>
                                                    </select>
                                                    <small class="text-danger" v-if="errors.category_id">{{
                                                        errors.category_id[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="supplier_id">Supplier</label>
                                                    <select class="form-control" id="supplier_id"
                                                            v-model="form.supplier_id">
                                                        <option v-for="supplier in suppliers" :value="supplier.id">
                                                            {{supplier.name}}
                                                        </option>
                                                    </select>
                                                    <small class="text-danger" v-if="errors.supplier_id">{{
                                                        errors.supplier_id[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="root">Root</label>
                                                    <input type="text" class="form-control" id="root"
                                                           placeholder="Enter Product Root" v-model="form.root">
                                                    <small class="text-danger" v-if="errors.root">{{ errors.root[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="buying_price">Buying Price</label>
                                                    <input type="text" class="form-control" id="buying_price"
                                                           placeholder="Enter Buying Price" v-model="form.buying_price">
                                                    <small class="text-danger" v-if="errors.buying_price">{{
                                                        errors.buying_price[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="selling_price">Selling Price</label>
                                                    <input type="text" class="form-control" id="selling_price"
                                                           placeholder="Enter Selling Price"
                                                           v-model="form.selling_price">
                                                    <small class="text-danger" v-if="errors.selling_price">{{
                                                        errors.selling_price[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="buying_date">Buying Date</label>
                                                    <input type="date" class="form-control" id="buying_date"
                                                           placeholder="Enter Buying Date" v-model="form.buying_date">
                                                    <small class="text-danger" v-if="errors.buying_date">{{
                                                        errors.buying_date[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="text" class="form-control" id="quantity"
                                                           placeholder="Enter Product Quantity" v-model="form.quantity">
                                                    <small class="text-danger" v-if="errors.quantity">{{
                                                        errors.quantity[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <label for="customFile">Image</label>
                                                        <input type="file" class="custom-file-input" id="customFile"
                                                               @change="onFileSelected">
                                                        <small class="text-danger" v-if="errors.image"> {{
                                                            errors.image[0] }}
                                                        </small>
                                                        <label class="custom-file-label" for="customFile">Choose
                                                            image</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <img :src="form.image" style="height: 40px; width: 40px;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
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
        name: "create",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            } else {
                axios.get('/api/categories', this.form)
                    .then(({data}) => this.categories = data);
                axios.get('/api/suppliers', this.form)
                    .then(({data}) => this.suppliers = data);
            }
        },
        data() {
            return {
                form: {
                    name: null,
                    code: null,
                    category_id: null,
                    supplier_id: null,
                    root: null,
                    buying_price: null,
                    selling_price: null,
                    buying_date: null,
                    quantity: null,
                    image: null,
                },
                errors: {},
                categories: {},
                suppliers: {}
            }
        },
        methods: {
            onFileSelected(event) {
                let file = event.target.files[0];
                if (file.size > 1048770) {
                    Notification.image_validation();
                } else {
                    let reader = new FileReader();
                    reader.onload = event => {
                        this.form.image = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            productInsert() {
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/products', this.form)
                        .then(res => {
                            if (res.data.status) {
                                this.$router.push({name: 'products'});
                                Notification.success();
                            } else {
                                Notification.error();
                            }
                        })
                        .catch(err => {
                            if (typeof (err.response.data.errors) != 'undefined') {
                                this.errors = err.response.data.errors
                            } else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status) {
                                Notification.customError(err.response.data.message);
                            } else {
                                console.log(err);
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
