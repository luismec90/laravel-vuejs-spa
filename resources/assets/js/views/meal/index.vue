<template>
	<div>
        <div class="row page-titles">
            <div class="col-xs-12">
                <h3 class="text-center">Meals</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
				<button type="button" class="btn btn-primary pull-right" @click.prevent="$refs.mealAddForm.showModal">
					Add meal
				</button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
				<h4>Filter Meals</h4>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="">Date</label>
							<date-picker v-model="from_date" :format="'dd MMM yyyy'" :disabled="{ from: new Date() }" input-class="form-control"></date-picker>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label for="">Time</label>
							<div>
								<time-picker format="hh:mm:ss A" v-model="from_time"></time-picker>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="">To</label>
							<date-picker v-model="to_date" :format="'dd MMM yyyy'" :disabled="{ from: new Date() }" input-class="form-control"></date-picker>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label for="">Time</label>
							<div>
								<time-picker format="hh:mm:ss A" v-model="to_time"></time-picker>
							</div>
						</div>
					</div>

					<div class="col-xs-6">
						<div class="form-group filter-btns">
							<button class="btn btn-warning" @click.prevent="cleanFilterMealForm">Clean</button>
							<button class="btn btn-primary" @click.prevent="searchMeals">Search</button>
						</div>
					</div>
				</div>
            </div>
        </div>

		<div class="row">
			<div class="col-xs-12">
				<h4>Meal List ({{totalCalories}} calories)</h4>
				<h6 v-if="meals.total">Total {{meals.total}} result found!</h6>
				<h6 v-else>No result found!</h6>
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<table class="table" v-if="meals.total">
								<thead>
									<tr>
										<th>Name</th>
										<th>Calories</th>
										<th>Eaten on</th>
										<th style="width:100px;">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="meal in meals.data">
										<td>{{ meal.name }}</td>
										<td>{{ meal.calories }}</td>
										<td>{{ meal.datetime | beautifyDate }}</td>
										<td>
											<button class="btn btn-info btn-sm" @click.prevent="$refs.mealEditForm.showModal(meal)" data-toggle="tooltip" title="Edit Meal"><i class="fa fa-pencil-alt"></i></button>
											<button class="btn btn-danger btn-sm"@click.prevent="$refs.mealDeleteForm.showModal(meal)" data-toggle="tooltip" title="Delete meal"><i class="fa fa-trash"></i></button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<pagination :data="meals" :limit=3 v-on:pagination-change-page="getMeals"></pagination>
					</div>
					<div class="col-md-4">
						<div class="float-right">
							<select name="pageLength" class="form-control" v-model="filterMealForm.pageLength" @change="getMeals" v-if="meals.total">
								<option value="5">5 per page</option>
								<option value="10">10 per page</option>
								<option value="25">25 per page</option>
								<option value="100">100 per page</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

		<meal-add-form ref="mealAddForm" v-on:getMeals="getMeals"></meal-add-form>
		<meal-edit-form ref="mealEditForm" v-on:getMeals="getMeals"></meal-edit-form>
		<meal-delete-form ref="mealDeleteForm" v-on:getMeals="getMeals"></meal-delete-form>

	</div>
</template>

<script>

	import mealAddForm from './add'
	import mealEditForm from './edit'
	import mealDeleteForm from './delete'
    import datePicker from 'vuejs-datepicker'
	import timePicker from 'vue2-timepicker'
    import pagination from 'laravel-vue-pagination'
    import helper from '../../services/helper'

    export default {
        components : { mealAddForm, mealEditForm, mealDeleteForm, pagination, datePicker, timePicker },
        data() {
            return {
				totalCalories: 0,
			    meals: {},
				from_date: '',
				from_time: {
				  hh: '',
				  mm: '',
				  ss: '',
				  A: ''
				},
				to_date: '',
				to_time: {
				  hh: '',
				  mm: '',
				  ss: '',
				  A: ''
				},
				filterMealForm: {
                    from_date: '',
					from_time: '',
					to_date: '',
					to_time: '',
                    pageLength: 10
                }
            }
        },

        created() {
            this.getMeals();
        },
        methods: {
            getMeals(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterMealForm);
                axios.get('/api/v1/meal?page=' + page + url)
                    .then(response => {
						this.meals = response.data.pagination
						this.totalCalories = response.data.total_calories
						this.$store.dispatch('setAuthUserDetail',{
							today_eaten_calories: parseInt(response.data.today_eaten_calories),
						});
					}).catch(error => {
						toastr['error'](error.response.data.message);
					});
            },
			searchMeals(page) {
				this.filterMealForm.from_date = this.$options.filters.date(this.from_date);
				if (!helper.isTimeObjectEmpty(this.from_time)) {
					this.filterMealForm.from_time = this.$options.filters.time(this.from_time);
				}
				this.filterMealForm.to_date = this.$options.filters.date(this.to_date);
				if (!helper.isTimeObjectEmpty(this.to_time)) {
					this.filterMealForm.to_time = this.$options.filters.time(this.to_time);
				}
				this.getMeals();
			},
			cleanFilterMealForm() {
				this.from_date = '';
				this.from_time = {
					hh: '',
					mm: '',
					ss: '',
					A: ''
			  	};
				this.to_date = '';
				this.to_time = {
					hh: '',
					mm: '',
					ss: '',
					A: ''
				};

				this.filterMealForm.from_date = '';
				this.filterMealForm.from_time = '';
				this.filterMealForm.to_date = '';
				this.filterMealForm.to_time = '';

				this.getMeals();
			}
        },
        filters: {
          	date(datetime) {
				return helper.getDate(datetime);
			},
			time(time) {
				return helper.getTime(time);
			},
			beautifyDate(datetime) {
				return helper.beautifyDate(datetime);
			}
        }
    }
</script>
