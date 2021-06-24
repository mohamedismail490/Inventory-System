<template>
    <div>
        <div class="row">
            <router-link to="/salaries" class="btn btn-primary">Go Back</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Employees Salaries</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Joining Date</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Salary Amount</th>
                                <th>Salary Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="salary in filterSearch" :key="salary.id">
                                <td>{{ salary.employee.name }}</td>
                                <td>{{ salary.employee.join_date }}</td>
                                <td>{{ salary.salary_month }}</td>
                                <td>{{ salary.salary_year }}</td>
                                <td>{{ salary.amount }}</td>
                                <td>{{ salary.salary_date }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-salary', params:{id:salary.id}}"
                                                     class="btn btn-sm btn-primary">Edit Salary
                                        </router-link>
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
        name: "viewSalary",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            }
        },
        data() {
            return {
                salaries: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.salaries.filter(salary => {
                    return salary.employee.name.match(this.searchTerm)
                        || salary.salary_year.match(this.searchTerm)
                        || salary.amount.match(this.searchTerm)
                })
            }
        },

        methods: {
            monthSalaries() {
                let id = this.$route.params.id;
                axios.get('/api/employees/salaries/view/' + id)
                    .then(({data}) => {
                        this.salaries = data;
                    })
                    .catch()
            }
        },
        mounted() {
            this.monthSalaries();
        }
    }
</script>

<style scoped>

</style>
