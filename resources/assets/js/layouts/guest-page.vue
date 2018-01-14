<template>
    <div class="cover">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            toastr.options = {
                "positionClass": "toast-bottom-right"
            };
        }
    }
</script>
