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
                                        <h1 class="h4 text-gray-900 mb-4">Pay Salary</h1>
                                    </div>
                                    <form class="user" @submit.prevent="employeePaySalary">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name"><b>Name</b></label>
                                                    <input type="text" class="form-control" id="name"
                                                           v-model="form.name">
                                                    <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email"><b>Email</b></label>
                                                    <input type="email" class="form-control" id="email"
                                                           v-model="form.email">
                                                    <small class="text-danger" v-if="errors.email">{{ errors.email[0]
                                                        }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile"><b>Mobile</b></label>
                                                    <input type="text" class="form-control" id="mobile"
                                                           v-model="form.mobile">
                                                    <small class="text-danger" v-if="errors.mobile">{{
                                                        errors.mobile[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="salary"><b>Salary</b></label>
                                                    <input type="text" class="form-control" id="salary"
                                                           v-model="form.salary">
                                                    <small class="text-danger" v-if="errors.salary">{{ errors.salary[0]
                                                        }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="salary_month"><b>Months</b></label>
                                                    <select class="form-control" id="salary_month"
                                                            v-model="form.salary_month">
                                                        <option value="January">January</option>
                                                        <option value="February">February</option>
                                                        <option value="March">March</option>
                                                        <option value="April">April</option>
                                                        <option value="May">May</option>
                                                        <option value="June">June</option>
                                                        <option value="July">July</option>
                                                        <option value="August">August</option>
                                                        <option value="September">September</option>
                                                        <option value="October">October</option>
                                                        <option value="November">November</option>
                                                        <option value="December">December</option>
                                                    </select>
                                                    <small class="text-danger" v-if="errors.salary_month">{{
                                                        errors.salary_month[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="salary_year"><b>Year</b></label>
                                                    <select class="form-control" id="salary_year"
                                                            v-model="form.salary_year">
                                                        <option :value="(new Date().getFullYear()) - 1">{{(new Date().getFullYear()) - 1}}</option>
                                                        <option :value="new Date().getFullYear()">{{new Date().getFullYear()}}</option>
                                                    </select>
                                                    <small class="text-danger" v-if="errors.salary_year">{{ errors.salary_year[0]
                                                        }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Pay Now</button>
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
                let id = this.$route.params.id;
                axios.get('/api/employees/' + id)
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
                    email: '',
                    mobile: '',
                    salary: '',
                    salary_month: '',
                    salary_year: '',
                },
                errors: {}
            }
        },
        methods: {
            employeePaySalary() {
                let id = this.$route.params.id;
                if (!User.loggedIn()) {
                    this.$router.push({name: '/'})
                } else {
                    axios.post('/api/employees/' + id + '/pay_salary', this.form)
                        .then(res => {
                            if (res.data.status) {
                                this.$router.push({name: 'given-salaries'});
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
