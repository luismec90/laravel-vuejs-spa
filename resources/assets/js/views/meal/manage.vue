<template>
	<div>
        <div class="row page-titles">
            <div class="col-xs-12">
                <h3 class="text-center">Meals</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
				<button type="button" class="btn btn-primary pull-right" @click.prevent="$refs.mealAddForm.showModal()">
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
							<label for="">User email</label>
							<input type="email" class="form-control" v-model="filterMealForm.user_email"  @keyup.enter="getMeals()">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="">User name</label>
							<input type="name" class="form-control" v-model="filterMealForm.user_name"  @keyup.enter="getMeals()">
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group filter-btns">
							<button class="btn btn-warning" @click.prevent="cleanFilterMealForm()">Clean</button>
							<button class="btn btn-primary" @click.prevent="getMeals()">Search</button>
						</div>
					</div>
				</div>
            </div>
        </div>

		<div class="row">
			<div class="col-xs-12">
				<h4>Meal List</h4>
				<h6 v-if="meals.total">Total {{meals.total}} result found!</h6>
				<h6 v-else>No result found!</h6>
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							<table class="table" v-if="meals.total">
								<thead>
									<tr>
										<th>User</th>
										<th>Email</th>
										<th>Meal</th>
										<th>Calories</th>
										<th>Eaten on</th>
										<th style="width:100px;">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="meal in meals.data">
										<td>{{ meal.user.name }}</td>
										<td>{{ meal.user.email }}</td>
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
				<pagination :data="meals" :filterDataForm="filterMealForm" v-on:updateTable="getMeals"></pagination>
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
    import datepicker from 'vuejs-datepicker'
    import pagination from '../shared-components/pagination'
    import helper from '../../services/helper'

    export default {
        components : { mealAddForm, mealEditForm, mealDeleteForm, pagination, datepicker },
        data() {
            return {
			    meals: {},
				filterMealForm: {
                    user_email: '',
					user_name: '',
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
                axios.get('/api/v1/manage/meal?page=' + page + url)
                    .then(response => {
						this.meals = response.data;
					}).catch(error => {
						toastr['error'](error.response.data.message);
					});
            },
			cleanFilterMealForm() {
				this.filterMealForm.user_email = '';
				this.filterMealForm.user_name = '';
				this.getMeals();
			}
        },
        filters: {
          	date(datetime) {
				return helper.getDate(datetime);
			},
			beautifyDate(datetime) {
				return helper.beautifyDate(datetime);
			}
        }
    }
</script>
