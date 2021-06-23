<template>
    <div>
        <div class="row">
            <router-link to="/store-expense" class="btn btn-primary">Add Expense</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Expenses List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="pl-3 pr-3">Details</th>
                                <th class="text-center pl-1 pr-1">Amount</th>
                                <th class="text-center pl-1 pr-1">Expense Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="expense in filterSearch" :key="expense.id">
                                <td class="pl-3 pr-3">{{ expense.details_limited }}</td>
                                <td class="text-center pl-1 pr-1">{{ expense.amount }}</td>
                                <td class="text-center pl-1 pr-1">{{ expense.exp_date }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-expense', params:{id:expense.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
                                        <a @click="deleteExpense(expense.id)" class="btn btn-sm btn-danger" style="color: #ffffff;">Delete</a>
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
                expenses: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.expenses.filter(expense => {
                    return expense.details.match(this.searchTerm)
                        || expense.exp_date.match(this.searchTerm)
                })
            }
        },

        methods: {
            allExpenses() {
                axios.get('/api/expenses')
                    .then(({data}) => {
                        this.expenses = data;
                    })
                    .catch()
            },
            deleteExpense(id) {
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
                            axios.delete('/api/expenses/' + id)
                                .then(res => {
                                    if (res.data.status) {
                                        this.expenses = this.expenses.filter(expense => {
                                            return expense.id != id
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
                                    this.$router.push({name: 'expenses'});
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
            this.allExpenses();
        }
    }
</script>

<style scoped>
</style>
