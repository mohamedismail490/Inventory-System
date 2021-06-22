<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-4 col-md-4">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Register</h1>
                                    </div>
                                    <form class="user" @submit.prevent="signup">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name"
                                                   placeholder="Enter Your Full Name" v-model="form.name">
                                            <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" v-model="form.email"
                                                   aria-describedby="emailHelp"
                                                   placeholder="Enter Email Address">
                                            <small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password"
                                                   v-model="form.password"
                                                   placeholder="Password">
                                            <small class="text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                   v-model="form.password_confirmation"
                                                   placeholder="Confirm Password">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <router-link to="/" class="font-weight-bold small">Already have an account?</router-link>
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

<script>
    export default {
        name: "register",
        created(){
            if (User.loggedIn()){
                this.$router.push({ name: 'home'})
            }
        },
        data(){
            return {
                form: {
                    name: null,
                    email: null,
                    password: null,
                    confirm_password: null
                },
                errors: {}
            }
        },
        methods:{
            signup(){
                axios.post('/api/auth/signup', this.form)
                    .then(res => {
                        User.responseAfterLogin(res);
                        Toast.fire({
                            icon: 'success',
                            title: 'Signed In successfully!'
                        });
                        this.$router.push({ name: 'home'});
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
        }
    }
</script>

<style scoped>

</style>
