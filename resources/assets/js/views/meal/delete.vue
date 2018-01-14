<template>
    <div class="modal fade" id="delete-meal-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @submit.prevent="destroyMeal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Delete Meal</h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete {{mealForm.name}}?
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
            mealForm: new Form({
               'id' : '',
               'name' : '',
           }),
        }
    },
    methods: {
        showModal(meal) {
            this.mealForm.id = meal.id;
            this.mealForm.name = meal.name;
            $('#delete-meal-modal').modal('show');
        },
        destroyMeal(meal) {
            axios.delete('/api/v1/meal/'+this.mealForm.id).then(response => {
                toastr['success'](response.data.message);
                this.$emit('getMeals');
                $('#delete-meal-modal').modal('hide');
            }).catch(error => {
                toastr['error'](error.response.data.message);
            });
        }
    }
}

</script>
