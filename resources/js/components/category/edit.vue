<template>
    <div>
        <div class="row">
            <router-link to="/categories" class="btn btn-primary">All Categories</router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Edit Category</h1>
                                    </div>
                                    <form class="user" @submit.prevent="categoryUpdate" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="name"
                                                           placeholder="Enter Category Name" v-model="form.name">
                                                    <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
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
        name: "edit",
        created(){
            if (!User.loggedIn()){
                this.$router.push({ name: '/'})
            }else {
                let id = this.$route.params.id;
                axios.get('/api/categories/'+id)
                    .then(({data}) => {
                        this.form = data;
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
        data(){
            return {
                form: {
                    name: '',
                },
                errors: {}
            }
        },
        methods:{
            categoryUpdate(){
                let id = this.$route.params.id;
                if (!User.loggedIn()){
                    this.$router.push({ name: '/'})
                }else{
                    axios.patch('/api/categories/'+id,this.form)
                        .then(res => {
                            if (res.data.status) {
                                this.$router.push({ name: 'categories'});
                                Notification.success();
                            }else {
                                Notification.error();
                            }
                        })
                        .catch(err => {
                            if (typeof(err.response.data.errors) != 'undefined'){
                                this.errors = err.response.data.errors
                            }else if ((typeof (err.response.data.status) != 'undefined') && !err.response.data.status){
                                Notification.customError(err.response.data.message);
                            }else {
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
