<template>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Meal Calories</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><router-link to="/" exact>My meals</router-link></li>
                    <li><router-link to="/manage-users" v-if="isAdminOrManager()">Manage Users</router-link></li>
                    <li><router-link to="/manage-meals" v-if="isAdmin()">Manage Meals</router-link></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a v-bind:class="{ 'text-success': isCaloriesGoalAchieved, 'text-danger': !isCaloriesGoalAchieved }">{{ getUserTodayEatenCalories() }}  calories eaten today</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ getAuthUserName() }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <router-link to="/profile"><i class="fa fa-user"></i> My Profile</router-link>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#" @click.prevent="logout"><i class="fa fa-power-off"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</template>

<script>

    import helper from '../services/helper'

    export default {
        mounted() {
        },
        methods : {
            logout() {
                helper.logout().then(() => {
                    this.$store.dispatch('resetAuthUserDetail');
                    this.$router.replace('/login');
                })
            },
            getAuthUserName() {
                return this.$store.getters.getAuthUserName;
            },
            getUserTodayEatenCalories () {
                return this.$store.getters.getUserTodayEatenCalories;
            },
            getUserCaloriesPerDay () {
                return this.$store.getters.getUserCaloriesPerDay;
            },
            getAuthUser(name) {
                return this.$store.getters.getAuthUser(name);
            },
            isUser() {
                return this.$store.getters.getAuthUser('role') == 1;
            },
            isManager() {
                return this.$store.getters.getAuthUser('role') == 2;
            },
            isAdmin() {
                return this.$store.getters.getAuthUser('role') == 3;
            },
            isManager() {
                return this.$store.getters.getAuthUser('role') == 2;
            },
            isAdminOrManager() {
                return this.isManager() || this.isAdmin();
            }
        },
        computed: {
            isCaloriesGoalAchieved () {
                return this.getUserTodayEatenCalories() >= this.getUserCaloriesPerDay();
            },

        }
    }
</script>

<style>
.navbar ul > li > a.active,
.navbar ul > li > a.active:hover,
.navbar ul > li > a.active:focus {
    font-weight: 400;
    background: #3f5973;
    color: #18bc9c;
}
.navbar ul > li > a.text-success,
.navbar ul > li > a.text-success:hover,
.navbar ul > li > a.text-success:focus {
    color: #fff;
    background-color: #449d44;
}
.navbar ul > li > a.text-danger,
.navbar ul > li > a.text-danger:hover,
.navbar ul > li > a.text-danger:focus
 {
     color: #fff;
     background-color: #d9534f;
}
.filter-btns {
	margin-top: 25px;
}
</style>
