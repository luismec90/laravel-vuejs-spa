<template>
    <div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @submit.prevent="destroyUser">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Delete User</h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete {{userForm.name}}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger ">
                            Yes, delete
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
           }),
        }
    },
    methods: {
        showModal(user) {
            this.userForm.id = user.id;
            this.userForm.name = user.name;
            $('#delete-user-modal').modal('show');
        },
        destroyUser(user) {
            axios.delete('/api/v1/manage/user/' + this.userForm.id).then(response => {
                toastr['success'](response.data.message);
                this.$emit('getUsers');
                $('#delete-user-modal').modal('hide');
            }).catch(error => {
                toastr['error'](error.response.data.message);
            });
        }
    }
}

</script>
