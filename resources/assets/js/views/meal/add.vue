<template>
    <div class="modal fade" id="add-meal-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @submit.prevent="storeMeal"  autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Meal</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" v-if="$route.path === '/manage-meals'">
                            <label for="">User email</label>
                            <autocomplete
                                url="/api/v1/manage/user/autocomplete"
                                :classes="{ wrapper: 'form-wrapper', input: 'form-control', list: 'data-list', item: 'data-list-item' }"
                                anchor="email"
                                autocomplete="off"
                                label="name"
                                :customHeaders="{ Authorization: authorization }"
                                :on-select="autocompleteSelection"
                                :required="true"
                                ref="autocomplete"
                                >
                            </autocomplete>

                        </div>
                        <div class="form-group">
                            <label for="">Name</label>
                            <input class="form-control" type="text" value="" required="required" v-model="mealForm.name">
                        </div>
                        <div class="form-group">
                            <label for="">Calories</label>
                            <input class="form-control" type="number" value="" required="required" v-model="mealForm.calories">
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
                            Save
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
    import autocomplete from 'vue2-autocomplete-js'
    import 'vue2-autocomplete-js/dist/style/vue2-autocomplete.css'
    import helper from '../../services/helper'

    export default {
        components : { datePicker, timePicker, autocomplete },
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
				   'email' : '',
				   'name' : '',
				   'calories' : '',
				   'date' : '',
				   'datetime' : ''
			   })
           }
        },
        methods: {
            autocompleteSelection(data) {
                this.mealForm.email = data.email;
            },
			showModal() {
				this.cleanMealForm();
				$('#add-meal-modal').modal('show');
			},
			storeMeal() {
                if (helper.isTimeObjectEmpty(this.time)) {
                    toastr['error']('Please input a valid time.');
                    return;
                }
                if (this.$route.path === '/manage-meals' && this.mealForm.email == '') {
                    toastr['error']('Please select a valid user!');
                    return;
                }
				this.mealForm.datetime = this.$options.filters.fullDate(this.mealForm.date, this.time);
				this.mealForm.post('/api/v1/meal').then(response => {
					toastr['success'](response.message);
                	this.$emit('getMeals');
                	$('#add-meal-modal').modal('hide');
                	this.cleanMealForm();

				}).catch(response => {
					toastr['error'](response.message);
				});
			},
			cleanMealForm() {
				this.mealForm.id = '';
				this.mealForm.email = '';
				this.mealForm.name = '';
				this.mealForm.calories = '';
				this.mealForm.date = '';
				this.time = {
                  hh: '',
                  mm: '',
                  ss: '',
                  A: ''
                }
                if (this.$route.path === '/manage-meals') {
                    this.$refs.autocomplete.setValue('');
                }
			}
        },
        computed: {
            authorization() {
                return 'Bearer ' + localStorage.getItem('auth_token')
            }
        },
        filters: {
			fullDate(date, time) {
				return helper.getFullDate(date, time);
			},
        }
    }
</script>

<style>
    .data-list > ul{
        z-index: 9999;
        max-height: 300px;
        overflow-y: auto;
    }
</style>
