export default {
    logout() {
        return axios.post('/api/v1/auth/logout').then(response =>  {
            localStorage.removeItem('auth_token');
            axios.defaults.headers.common['Authorization'] = null;
            toastr['success'](response.data.message);
        }).catch(error => {
            toastr['success'](error);
        });
    },
    authUser() {
        return axios.get('/api/v1/auth/user').then(response =>  {
            return response.data;
        }).catch(error => {
            return error.response.data;
        });
    },
    check() {
        return axios.post('/api/v1/auth/check').then(response =>  {
            return !!response.data.authenticated;
        }).catch(error =>{
            return response.data.authenticated;
        });
    },
    getRoleName(role) {
        if(!role)
            return;

        if (role == 1) {
            return 'User';
        } else if (role == 2) {
            return 'Manager';
        } else if (role == 3) {
            return 'Admin';
        }
    },
    getFilterURL(data) {
        let url = '';
        $.each(data, function(key, value) {
            url += (value) ? '&'+key+'='+encodeURI(value) : '';
        });
        return url;
    },
    getDate(date) {
        if(!date)
            return;

        return moment(date).format('YYYY-MM-DD');
    },
    getTime(time) {
        if(!time)
            return;

        return moment(time.hh + ':' + time.mm + ':' + time.ss + ' ' + time.A, 'hh:mm:ss A').format('HH:mm:ss');
    },

    getHour(date) {
        if(!date)
            return;

        return moment(date).format('hh');
    },
    getMinute(date) {
        if(!date)
            return;

        return moment(date).format('mm');
    },
    getSecond(date) {
        if(!date)
            return;

        return moment(date).format('ss');
    },
    getPeriod(date) {
        if(!date)
            return;

        return moment(date).format('A');
    },
    getFullDate(date, time) {
        if(!date || !time)
            return;

        date = moment(date).format('YYYY-MM-DD');
        time = moment(time.hh + ':' + time.mm + ':' + time.ss + ' ' + time.A, 'hh:mm:ss A').format('HH:mm:ss');
        return date + ' ' + time;
    },
    beautifyDate(datetime) {
        if(!datetime)
            return;

        return moment(datetime,'YYYY-MM-DD HH:mm:ss').format('DD MMM YYYY hh:mm:ss A');
    },
    isTimeObjectEmpty(time) {
        if (time.hh == '' || time.mm == '' || time.ss == '' || time.A == '')
            return true

        return false;
    }
}
