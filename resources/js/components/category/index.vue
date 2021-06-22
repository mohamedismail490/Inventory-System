<template>
    <div>
        <div class="row">
            <router-link to="/store-category" class="btn btn-primary">Add Category</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Categories List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="category in filterSearch" :key="category.id">
                                <td>{{ category.name }}</td>
                                <td class="text-center">{{ category.created_at }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-category', params:{id:category.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
                                        <a @click="deleteCategory(category.id)" class="btn btn-sm btn-danger" style="color: #ffffff;">Delete</a>
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
                categories: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.categories.filter(category => {
                    return category.name.match(this.searchTerm)
                        || category.created_at.match(this.searchTerm)
                })
            }
        },

        methods: {
            allCategories() {
                axios.get('/api/categories')
                    .then(({data}) => {
                        this.categories = data;
                    })
                    .catch()
            },
            deleteCategory(id) {
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
                            axios.delete('/api/categories/' + id)
                                .then(res => {
                                    console.log(res.data);
                                    if (res.data.status) {
                                        this.categories = this.categories.filter(category => {
                                            return category.id != id
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
                                    this.$router.push({name: 'categories'});
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
            this.allCategories();
        }
    }
</script>

<style scoped>
</style>
