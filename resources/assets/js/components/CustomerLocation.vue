<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">Customer Overview</div>
        <customer-details-header></customer-details-header>
        <form class="form-horizontal">

            <div class="panel panel-info">
                <div class="panel-heading">Location List</div>
                <div class="panel-body">
                    <table class="table table-striped text-center">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Zone</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr v-for="location in locations">
                            <td>{{ location.name }}</td>
                            <td>{{ location.address }}</td>
                            <td>{{ location.city }}</td>
                            <td>{{ location.state }}</td>
                            <td>{{ location.zip }}</td>
                            <td>{{ location.zone }}</td>

                            <td>
                                <router-link :to="{name:'edit-location',params:{id:location.id}}" tag="button" class="btn btn-primary">Edit</router-link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import CustomerHeader from './CustomerHeader.vue'

    export default {
        components:{
            customerDetailsHeader: CustomerHeader,
        },
        data(){
            return{
                locations: [],
                id: this.$route.params.id,
            }
        },
        created() {
            axios.get('customer-locations/all/'+this.id).then(response => {
                this.locations=response.data;
            });
        },
        methods:{

        }
    }
</script>

<style>

</style>