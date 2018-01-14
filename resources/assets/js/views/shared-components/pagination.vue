<template>
    <div class="row">
        <div class="col-md-8">
            <pagination :data="data" :limit=3 v-on:pagination-change-page="updateTable"></pagination>
        </div>
        <div class="col-md-4">
            <div class="float-right">
                <select name="pageLength" class="form-control" v-model="filterForm.pageLength" @change="updateTable" v-if="data.total">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    import pagination from 'laravel-vue-pagination'
    export default {
        components : {  pagination },
        props : ['data', 'filterDataForm'],
        data() {
            return {
                filterForm: {}
            }
        },
        created() {
            this.filterForm = this.filterDataForm;
        },
        methods: {
            updateTable(data) {
                this.$emit('updateTable', data)
            }
        }
    }
</script>
