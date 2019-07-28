<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">Customer Overview</div>
        <customer-details-header></customer-details-header>

        <form class="form-horizontal">

            <div class="panel panel-info">
                <div class="panel-heading">Contract List</div>
                <div class="panel-body">
                    <table class="table table-striped text-center">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Balance</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr v-for="contract in contracts">
                            <td>{{ contract.name  }}</td>
                            <td>{{ contract.type }}</td>

                            <td v-if="contract.status==1">Active</td>
                            <td v-if="contract.status==2">Pending</td>
                            <td v-if="contract.status==3">Cancelled</td>
                            <td v-if="contract.status==4">Expired</td>
                            <td v-if="contract.status==5">Closed</td>

                            <td>{{ contract.start_date }}</td>
                            <td>{{ contract.end_date }}</td>

                            <td v-if="contract.hour_limit">{{ contract.hour_limit }} Hours</td>
                            <td v-if="contract.money_limit">{{ contract.money_limit }}/-</td>
                            <td v-if="contract.unlimited">Unlimited</td>

                            <td>
                                <router-link :to="{name:'edit-contact',params:{id:contract.id}}" tag="button" class="btn btn-primary">Edit</router-link>
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
                contracts: [],
                id: this.$route.params.id,
            }
        },
        created() {
            axios.post('contract-customer', {id: this.id}).then(response => {
                this.contracts = response.data;
            })
        },
        methods:{

        }
    }
</script>

<style>

</style>