<template>
    <div class="modal fade" id="add-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @submit.prevent="storeUser">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input class="form-control" type="text" value="" v-model="userForm.name">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" type="text" value="" v-model="userForm.email">
                        </div>
                        <div class="form-group">
                            <label for="">Calories per day</label>
                            <input class="form-control" type="number" value="" v-model="userForm.calories_per_day">
                        </div>
                        <div class="form-group" v-if="$store.getters.getAuthUserRole == 3">
                            <label for="">Role</label>
                            <select class="form-control" v-model="userForm.role">
                                <option value="">Select...</option>
                                <option value="1">User</option>
                                <option value="2">Manager</option>
                                <option value="3">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="" v-model="userForm.password">
                        </div>
                        <div class="form-group">
                            <label for="">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="" v-model="userForm.password_confirmation">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info waves-effect waves-light">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>

<script>
    import helper from '../../services/helper'

    export default {
        data() {
            return {
                userForm: new Form({
				   'id' : '',
				   'name' : '',
				   'email' : '',
				   'calories_per_day' : '',
				   'role' : '',
				   'password' : '',
				   'password_confirmation' : ''
			   }),
            }
        },
        methods: {
			showModal() {
				this.cleanUserForm();
				$('#add-user-modal').modal('show');
			},
            storeUser() {
				axios.post('/api/v1/manage/user', this.userForm).then(response =>  {
                    toastr['success'](response.data.message);
					this.$emit('getUsers',response.user);
                	$('#add-user-modal').modal('hide');
					this.cleanUserForm();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
			},
            cleanUserForm() {
				this.userForm.id = '';
				this.userForm.name = '';
				this.userForm.email = '';
				this.userForm.calories_per_day = '';
				this.userForm.role = '';
				this.userForm.password = '';
				this.userForm.password_confirmation = '';
			}
        }
    }
</script>
