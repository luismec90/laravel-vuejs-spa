<template>
    <div class="modal fade" id="edit-meal-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @submit.prevent="updateMeal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Meal</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input class="form-control" type="text" value="" v-model="mealForm.name">
                        </div>
                        <div class="form-group">
                            <label for="">Calories</label>
                            <input class="form-control" type="text" value="" v-model="mealForm.calories">
                        </div>
                        <div class="form-group">
                            <label for="">Date</label>
                            <date-picker v-model="mealForm.date" :format="'dd MMM yyyy'" :disabled="{ from: new Date() }" input-class="form-control" :required="true"></date-picker>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="">Time</label>
                                    <div>
                                        <time-picker format="hh:mm:ss A" v-model="time"></time-picker>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info waves-effect waves-light">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>
<script>
    import datePicker from 'vuejs-datepicker'
    import timePicker from 'vue2-timepicker'
    import helper from '../../services/helper'

    export default {
        components : { datePicker, timePicker },
        data() {
            return {
                time: {
                    hh: '',
                    mm: '',
                    ss: '',
                    A: ''
                },
				mealForm: new Form({
				   'id' : '',
				   'name' : '',
				   'calories' : '',
				   'datetime' : ''
			   }),
            }
        },
        methods: {
            showModal(meal) {
				this.mealForm.id = meal.id;
				this.mealForm.name = meal.name;
				this.mealForm.calories = meal.calories;
				this.mealForm.date = meal.datetime;
                this.time = {
                    hh: this.$options.filters.hour(meal.datetime),
                    mm: this.$options.filters.minute(meal.datetime),
                    ss: this.$options.filters.second(meal.datetime),
                    A: this.$options.filters.period(meal.datetime)
                }
				$('#edit-meal-modal').modal('show');
            },
			updateMeal() {
                if (helper.isTimeObjectEmpty(this.time)) {
                    toastr['error']('Please input a valid time.');
                    return;
                }
				this.mealForm.datetime = this.$options.filters.fullDate(this.mealForm.date, this.time);
				this.mealForm.patch('/api/v1/meal/'+this.mealForm.id)
				.then(response => {
					toastr['success'](response.message);
                    this.$emit('getMeals');
					$('#edit-meal-modal').modal('hide');
				})
				.catch(response => {
					toastr['error'](response.message);
				});
			}
        },
        filters: {
          	date(datetime) {
				return helper.getDate(datetime);
			},
			hour(datetime) {
				return helper.getHour(datetime);
			},
			minute(datetime) {
				return helper.getMinute(datetime);
			},
            second(datetime) {
                return helper.getSecond(datetime);
            },
			period(datetime) {
				return helper.getPeriod(datetime);
			},
			fullDate(date, hour, min, period) {
				return helper.getFullDate(date, hour, min, period);
			},
        }
    }
</script>
