<template>
    <section>
        <div class="login-box">
            <div>
                <form class="form-horizontal form-material" id="loginform" @submit.prevent="submit">
                    <h3 class="box-title m-b-20">Sign In</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" v-model="loginForm.email" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" v-model="loginForm.password" required="true">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>
                                Don't have an account?
                                <router-link to="/register" class="text-info"><b>Sign Up</b></router-link>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

</template>
<script>
    import helper from '../../services/helper'

    export default {
        data() {
            return {
                loginForm: {
                    email: '',
                    password: ''
                }
            }
        },
        methods: {
            submit(e) {
                axios.post('/api/v1/auth/login', this.loginForm).then(response =>  {
                    localStorage.setItem('auth_token',response.data.token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token');
                    toastr['success'](response.data.message);
                    this.$router.push('/')
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            }
        }
    }
</script>
