<template>
    <div>
        <form class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-heading">{{customerDetails.customerName}}</div>

                <customer-details-header></customer-details-header>

                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">Primary Contact</div>
                                    <div style="padding: 10px;">
                                        <p>{{customerDetails.contact.name}}</p>
                                        <p>{{customerDetails.contact.job_title}}</p>
                                        <p>{{customerDetails.contact.email}}</p>
                                        <p>{{customerDetails.contact.mobile_phone}} <small>(mobile)</small></p>
                                        <p if="customerDetails.contact.home_phone!=''">{{customerDetails.contact.home_phone}} <small>(home)</small></p>
                                        <p if="customerDetails.contact.fax!=''">{{customerDetails.contact.fax}} <small>(fax)</small></p>
                                    </div>
                                <div class="panel-body">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">Primary Location</div>
                                <div style="padding: 10px;">
                                    <p>{{customerDetails.location.name}}</p>
                                    <p>{{customerDetails.location.address}}</p>
                                    <p>{{customerDetails.location.city}}, {{customerDetails.location.state}} {{customerDetails.location.zip}},</p>
                                    <p>{{customerDetails.location.country}}</p>
                                </div>
                                <div class="panel-body">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>
</template>

<script>
    import axios from 'axios'
    import CustomerHeader from './CustomerHeader.vue'

    export default {
        components:{
            customerDetailsHeader: CustomerHeader,
        },
        data(){
            return{
                customerDetails: [],
                id: this.$route.params.id,
            }

        },
        created(){
            axios.get('customer-overview/'+this.id).then(response => {
                this.customerDetails = response.data;
            });
        },
        methods:{

        }
    }

</script>