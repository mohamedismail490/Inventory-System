<template>
    <div>
        <div class="row">
            <router-link to="/given-salaries" class="btn btn-primary">Pay Salary</router-link>
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
                        <h6 class="m-0 font-weight-bold text-primary">Paid Salaries Months List</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>Month</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="salary in filterSearch" :key="salary.id">
                                <td>{{ salary.salary_month }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'view-salaries', params:{id:salary.salary_month}}"
                                                     class="btn btn-sm btn-primary">view Salaries
                                        </router-link>&nbsp;
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
                salaries: [],
                searchTerm: ''
            }
        },
        computed: {
            filterSearch() {
                return this.salaries.filter(salary => {
                    return salary.salary_month.match(this.searchTerm);
                })
            }
        },

        methods: {
            allSalaries() {
                axios.get('/api/employees/paid/salaries')
                    .then(({data}) => {
                        this.salaries = data;
                    })
                    .catch()
            }
        },
        mounted() {
            this.allSalaries();
        }
    }
</script>

<style scoped>

</style>
