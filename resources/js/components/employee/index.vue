<template>
    <div>
        <div class="row">
            <router-link to="/store-employee" class="btn btn-primary">Add Employee</router-link>
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
                            <tr v-for="employee in filtersearch" :key="employee.id">
                                <td><img :src="employee.photo" class="rounded em_photo"></td>
                                <td>{{ employee.name }}</td>
                                <td>{{ employee.email }}</td>
                                <td>{{ employee.mobile }}</td>
                                <td>{{ employee.salary }}</td>
                                <td>{{ employee.join_date }}</td>
                                <td>
                                    <div class="row h-100 justify-content-center align-items-center">
                                        <router-link :to="{name: 'edit-employee', params:{id:employee.id}}"
                                                     class="btn btn-sm btn-primary">Edit
                                        </router-link>&nbsp;
                                        <a @click="deleteEmployee(employee.id)" class="btn btn-sm btn-danger" style="color: #ffffff;">Delete</a>
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
                employees: [],
                searchTerm: ''
            }
        },
        computed: {
            filtersearch() {
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
            allEmployee() {
                axios.get('/api/employees')
                    .then(({data}) => {
                        this.employees = data;
                    })
                    .catch()
            },
            deleteEmployee(id) {
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
                            axios.delete('/api/employees/' + id)
                                .then(res => {
                                    if (res.data.status) {
                                        this.employees = this.employees.filter(employee => {
                                            return employee.id != id
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
                                    this.$router.push({name: 'employees'});
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
            this.allEmployee();
        }
    }
</script>

<style scoped>
    .em_photo {
        height: 40px;
        width: 40px;
    }
</style>
