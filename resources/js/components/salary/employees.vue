<template>
    <div>
        <div class="row">
            <router-link to="/salaries" class="btn btn-primary">All Salaries</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Employees List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Salary</th>
                                <th>Joining Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="employee in filterSearch" :key="employee.id">
                                <td><img :src="employee.photo" class="rounded em_photo"></td>
                                <td>{{ employee.name }}</td>
                                <td>{{ employee.email }}</td>
                                <td>{{ employee.mobile }}</td>
                                <td>{{ employee.salary }}</td>
                                <td>{{ employee.join_date }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'pay-salary', params:{id:employee.id}}"
                                                     class="btn btn-sm btn-primary">Pay Salary
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
        name: "employees",
        created() {
            if (!User.loggedIn()) {
                this.$router.push({name: '/'})
            }
        },
        data() {
            return {
                employees: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.employees.filter(employee => {
                    return employee.name.match(this.searchTerm)
                        || employee.email.match(this.searchTerm)
                        || employee.mobile.match(this.searchTerm)
                        || employee.salary.match(this.searchTerm)
                        || employee.join_date.match(this.searchTerm)
                })
            }
        },

        methods: {
            allEmployees() {
                axios.get('/api/employees')
                    .then(({data}) => {
                        this.employees = data;
                    })
                    .catch()
            }
        },
        mounted() {
            this.allEmployees();
        }
    }
</script>

<style scoped>
    .em_photo {
        height: 40px;
        width: 40px;
    }
</style>
