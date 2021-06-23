<template>
    <div>
        <div class="row">
            <router-link to="/expenses" class="btn btn-primary">All Expenses</router-link>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Edit Expense</h1>
                                    </div>
                                    <form class="user" @submit.prevent="expenseUpdate">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="details">Details</label>
                                                    <textarea class="form-control" id="details" rows="3"
                                                              placeholder="Enter Expense Details"
                                                              v-model="form.details"></textarea>
                                                    <small class="text-danger" v-if="errors.details">{{
                                                        errors.details[0] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input type="text" class="form-control" id="amount"
                                                           placeholder="Enter Expense Amount" v-model="form.amount">
                                                    <small class="text-danger" v-if="errors.amount">{{ errors.amount[0]
                                                        }}
                                                    </small>
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
                axios.get('/api/expenses/'+id)
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
                    details: '',
                    amount: '',
                },
                errors: {}
            }
        },
        methods:{
            expenseUpdate(){
                let id = this.$route.params.id;
                if (!User.loggedIn()){
                    this.$router.push({ name: '/'})
                }else {
                    axios.patch('/api/expenses/' + id, this.form)
                        .then(res => {
                            if (res.data.status) {
                                this.$router.push({name: 'expenses'});
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
