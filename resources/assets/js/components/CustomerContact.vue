<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">Customer Overview</div>
        <customer-details-header></customer-details-header>
        <form class="form-horizontal">

            <div class="panel panel-info">
                <div class="panel-heading">Contact List</div>
                <div class="panel-body">
                    <table class="table table-striped text-center">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Info</th>
                            <th>Location</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr v-for="contactDetail in contactDetails">
                            <td>
                                {{ contactDetail.contact.name  }}
                            </td>

                            <td>
                                {{ contactDetail.contact.email }}
                                <p>{{ contactDetail.contact.mobile_phone }}</p>
                            </td>
                            <td>{{ contactDetail.location.name }}</td>
                            <td>
                                <router-link :to="{name:'edit-contact',params:{id:contactDetail.contact.id}}" tag="button" class="btn btn-primary">Edit</router-link>
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
                contactDetails: [],
                id: this.$route.params.id,
            }
        },
        created() {
            axios.get('customer-contacts/all/'+this.id).then(response => {
                this.contactDetails=response.data;
            });
        },
        methods:{

        }
    }
</script>

<style>

</style>