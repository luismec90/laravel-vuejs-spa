<template>
	<div>
        <div class="row">
            <div class="col-xs-12">
                <h3 class="text-center">Users</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
				<button type="button" class="btn btn-primary pull-right" @click.prevent="$refs.userAddForm.showModal()">
					Add user
				</button>
            </div>
        </div>

		<div class="row">
			<div class="col-xs-12">
				<h4>Filter User</h4>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="">User email</label>
							<input type="email" class="form-control" v-model="filterUserForm.email" @keyup.enter="getUsers()">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="">User name</label>
							<input type="email" class="form-control" v-model="filterUserForm.name" @keyup.enter="getUsers()">
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group filter-btns">
							<button class="btn btn-warning" @click.prevent="cleanFilterUserForm()">Clean</button>
							<button class="btn btn-primary" @click.prevent="getUsers()">Search</button>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="row">
            <div class="col-xs-12">
                <h4>User List</h4>
                <h6 v-if="users.total">Total {{users.total}} result found!</h6>
                <h6 v-else>No result found!</h6>
                <div class="table-responsive">
                    <table class="table" v-if="users.total">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Calories per day</th>
                                <th v-if="$store.getters.getAuthUserRole == 3">Role</th>
                                <th style="width:180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.calories_per_day }}</td>
								<td v-if="$store.getters.getAuthUserRole == 3">{{ user.role | roleName }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" @click.prevent="$refs.userEditForm.showModal(user)" data-toggle="tooltip" title="Edit User"><i class="fa fa-pencil-alt"></i></button>
                                    <button v-if="user.role != 3" class="btn btn-danger btn-sm" @click.prevent="$refs.userDeleteForm.showModal(user)" data-toggle="tooltip" title="Delete user"><i class="fa fa-trash"></i></button>
                                    <button v-else class="btn btn-danger btn-sm disabled" data-toggle="tooltip" title="Admins cannot be deleted" disabled="disabled"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

		<pagination :data="users" :filterDataForm="filterUserForm" v-on:updateTable="getUsers"></pagination>

		<user-add-form ref="userAddForm" v-on:getUsers="getUsers"></user-add-form>
		<user-edit-form ref="userEditForm" v-on:getUsers="getUsers"></user-edit-form>
		<user-delete-form ref="userDeleteForm" v-on:getUsers="getUsers"></user-delete-form>

	</div>
</template>

<script>

	import userAddForm from './add'
	import userEditForm from './edit'
	import userDeleteForm from './delete'
    import pagination from '../shared-components/pagination'
    import helper from '../../services/helper'

    export default {
        components : { userAddForm, userEditForm, userDeleteForm, pagination },
        data() {
            return {
                users: {},
				filterUserForm: {
					email: '',
					name: '',
                    pageLength: 10
                }
            }
        },
        created() {
            this.getUsers();
        },
        methods: {
            getUsers(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
				let url = helper.getFilterURL(this.filterUserForm);
                axios.get('/api/v1/manage/user?page=' + page + url)
                    .then(response => this.users = response.data )
					.catch(error => {
	                    toastr['error'](error.response.data.message);
	                });
            },
			cleanFilterUserForm() {
				this.filterUserForm.email = '';
				this.filterUserForm.name = '';
				this.getUsers();
			}
        },
		filters: {
			roleName(role) {
				return helper.getRoleName(role);
			}
		}
    }
</script>
