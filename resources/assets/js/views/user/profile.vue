<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h4>Edit Profile</h4>
                    <form @submit.prevent="updateProfile">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input class="form-control" type="text" value="" v-model="user.name" required="true">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" type="text" value="" readonly="readonly" v-model="user.email">
                        </div>
                        <div class="form-group">
                            <label for="">Calories per day</label>
                            <input class="form-control" type="number" min="0" value="" v-model="user.calories_per_day" required="true">
                        </div>
                        <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Update</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h4>Change Password</h4>
                    <form @submit.prevent="changePassword">
                        <div class="form-group">
                            <label for="">Current Password</label>
                            <input class="form-control" type="password" value="" v-model="passwordForm.current_password" required="true">
                        </div>
                        <div class="form-group">
                            <label for="">New Password</label>
                            <input class="form-control" type="password" value="" v-model="passwordForm.new_password" required="true">
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Password</label>
                            <input class="form-control" type="password" value="" v-model="passwordForm.new_password_confirmation" required="true">
                        </div>
                        <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker'

    export default {
        components : { datepicker },
        data() {
            return {
                passwordForm: new Form({
                    current_password : '',
                    new_password : '',
                    new_password_confirmation : ''
                }),
                user: new Form({
                    name : '',
                    email : '',
                    calories_per_day : '',
                }, false),
                avatar: '',
                social_auth: 0
            };
        },
        mounted() {
            axios.get('/api/v1/auth/user').then(response => response.data).then(response => {
                this.user.name = response.user.name;
                this.user.email = response.user.email;
                this.user.calories_per_day = response.user.calories_per_day;
            });
        },
        methods: {
            changePassword() {
                this.passwordForm.post('/api/v1/user/change-password').then(response => {
                    toastr['success'](response.message);
                }).catch(response => {
                    toastr['error'](response.message);
                });
            },
            updateProfile() {
                this.user.patch('/api/v1/user/update-profile').then(response => {
                    toastr['success'](response.message);
                    this.$store.dispatch('setAuthUserDetail',{
                        name: this.user.name,
                        calories_per_day: parseInt(this.user.calories_per_day)
                    });
                }).catch(response => {
                    toastr['error'](response.message);
                });
            },
            getAuthUser(name) {
                return this.$store.getters.getAuthUser(name);
            }
        }
    }
</script>
