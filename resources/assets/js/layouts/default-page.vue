<template>
    <div id="main-wrapper">
        <app-header></app-header>
        <div class="page-wrapper">
            <div class="container-fluid">
                <router-view></router-view>
            </div>
        	<app-footer></app-footer>
        </div>
    </div>
</template>


<script>
    import AppHeader from './header.vue'
    import AppFooter from './footer.vue'
    import helper from '../services/helper'
    export default {
        methods : {
            getAuthUser(name) {
                return this.$store.getters.getAuthUser(name);
            }
        },
        components: {
            AppHeader, AppFooter
        },
        mounted() {
            toastr.options = {
                "positionClass": "toast-bottom-right"
            };

            if(!this.getAuthUser('email')) {
                helper.authUser().then(response => {
                    this.$store.dispatch('setAuthUserDetail',{
                        name: response.user.name,
                        email: response.user.email,
                        role: response.user.role,
                        calories_per_day: parseInt(response.user.calories_per_day),
                        today_eaten_calories: parseInt(response.user.today_eaten_calories),
                    });
                });
            }
        }
    }
</script>
