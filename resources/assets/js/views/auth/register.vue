<template>
    <section>
        <div class="register-box">
            <form class="form-horizontal form-material" id="registerform" @submit.prevent="submit">
                <h3 class="box-title m-b-20">Sign Up</h3>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <label for="">Full name</label>
                        <input type="text" class="form-control" name="name" v-model="registerForm.name" required="true">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <label for="">Email</label>
                        <input type="text" class="form-control" name="email" v-model="registerForm.email" required="true">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <label for="calories_per_day">Calories per day</label>
                        <input type="number" class="form-control" name="calories_per_day" min="1" v-model="registerForm.calories_per_day" required="true">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" v-model="registerForm.password" required="true">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="">Confirm password</label>
                        <input type="password" class="form-control" v-model="registerForm.password_confirmation" required="true">
                    </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Register</button>
                    </div>
                </div>
                <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <p>
                            Already have an account?
                            <router-link to="/login" class="text-info m-l-5">
                                <b>Sign In</b>
                            </router-link>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </section>

</template>
<script>
    export default {
        data() {
            return {
                registerForm: {
                    name: '',
                    email: '',
                    calories_per_day: '',
                    password: '',
                    password_confirmation: '',
                }
            }
        },
        methods: {
            submit(e) {
                axios.post('/api/v1/auth/register', this.registerForm).then(response =>  {
                    toastr['success'](response.data.message);
                    this.$router.push('/login');
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            }
        }
    }
</script>
