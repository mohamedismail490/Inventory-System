<template>
    <div>
        <div class="row">
            <router-link to="/employees" class="btn btn-primary">All Employees</router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Edit Employee</h1>
                                    </div>
                                    <form class="user" @submit.prevent="employeeUpdate()" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="name"
                                                           placeholder="Enter Full Name" v-model="form.name">
                                                    <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" id="email"
                                                           placeholder="Enter Email Address" v-model="form.email">
                                                    <small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="mobile"
                                                           placeholder="Enter Mobile Number" v-model="form.mobile">
                                                    <small class="text-danger" v-if="errors.mobile">{{ errors.mobile[0] }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="date" class="form-control" id="joining_date"
                                                           placeholder="Enter Joining Date" v-model="form.joining_date">
                                                    <small class="text-danger" v-if="errors.joining_date">{{ errors.joining_date[0] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="salary"
                                                           placeholder="Enter Salary" v-model="form.salary">
                                                    <small class="text-danger" v-if="errors.salary">{{ errors.salary[0] }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="nid"
                                                           placeholder="Enter National ID" v-model="form.nid">
                                                    <small class="text-danger" v-if="errors.nid">{{ errors.nid[0] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="address"
                                                           placeholder="Enter Address Details" v-model="form.address">
                                                    <small class="text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="customFile" @change="onFileSelected">
                                                        <small class="text-danger" v-if="errors.photo"> {{ errors.photo[0] }} </small>
                                                        <label class="custom-file-label" for="customFile">Choose image</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <img id="employeePhoto" :src="form.photo" style="height: 40px; width: 40px;">
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
                axios.get('/api/employees/'+id)
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
                    email: '',
                    mobile: '',
                    joining_date: '',
                    salary: '',
                    nid: '',
                    address: '',
                    photo: '',
                    newPhoto: ''
                },
                errors: {}
            }
        },
        methods:{
            onFileSelected(event){
                let file = event.target.files[0];
                if (file.size > 1048770) {
                    Notification.image_validation();
                }else{
                    let reader = new FileReader();
                    reader.onload = event =>{
                        this.form.newPhoto = event.target.result;
                        $("#employeePhoto").attr('src',event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            },
            employeeUpdate(){
                let id = this.$route.params.id;
                if (!User.loggedIn()){
                    this.$router.push({ name: '/'})
                }else {
                    axios.patch('/api/employees/' + id, this.form)
                        .then(res => {
                            if (res.data.status) {
                                this.$router.push({name: 'employees'});
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
