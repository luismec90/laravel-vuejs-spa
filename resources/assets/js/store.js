import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'

const store = new Vuex.Store({
	state: {
		auth: {
			name: '',
			email: '',
			role: '',
			calories_per_day: 0,
			today_eaten_calories: 0
		}
	},
	mutations: {
		setAuthUserDetail (state, auth) {
        	for (let key of Object.keys(auth)) {
                state.auth[key] = auth[key];
            }
		},
		resetAuthUserDetail (state) {
        	for (let key of Object.keys(state.auth)) {
                state.auth[key] = '';
            }
		},
		setUserTodayEatenCalories (state, todayEatenCalories) {
			state.auth['today_eaten_calories'] = todayEatenCalories;
		}
	},
	actions: {
		setAuthUserDetail ({ commit }, auth) {
     		commit('setAuthUserDetail', auth);
     	},
		setUserTodayEatenCalories ({ commit }, todayEatenCalories) {
			commit('setUserTodayEatenCalories', todayEatenCalories);
		},
     	resetAuthUserDetail ({commit}) {
     		commit('resetAuthUserDetail');
     	}
	},
	getters: {
		getAuthUser: (state) => (name) => {
		    return state.auth[name];
		},
		getAuthUserName: (state) => {
		    return state.auth['name'];
		},
		getAuthUserRole: (state) => {
			return state.auth['role'];
		},
		getUserCaloriesPerDay: (state) => {
			return state.auth['calories_per_day'];
		},
		getUserTodayEatenCalories: (state) => {
			return state.auth['today_eaten_calories'];
		}
	},
	plugins: [
		createPersistedState({ storage: window.sessionStorage })
	]
});

export default store;
