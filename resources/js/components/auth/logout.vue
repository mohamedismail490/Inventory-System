<template>
</template>

<script>
    export default {
        name: "logout",

        created(){
            User.logout();
            Toast.fire({
                icon: 'success',
                title: 'Logged out successfully!'
            });
            this.$router.push({ name: '/'})

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
                        }
                    })
            }
        }
    }
</script>

<style scoped>

</style>
