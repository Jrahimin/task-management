<template>
    <div class="container" style="width: 100%">
        <form class="form-horizontal" @submit.prevent="changeStatus">
            <fieldset>
                <legend>Change Status</legend>

                <p>{{ heading }}</p>

                <div class="form-group">
                    <label class="control-label col-md-2" >Status</label>

                    <div class="col-md-10">
                        <select class="form-control" v-model.number="status">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="2">Pending</option>
                            <option value="3">Cancelled</option>
                            <option value="4">Expired</option>
                            <option value="5">Closed</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-2 pull-right">
                        <button class="form-control btn btn-primary">Save</button>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['contractId', 'heading'],
        created() {
            axios.get('contract/'+this.contractId).then(response => {
                this.status = response.data.status;
            })
        },
        data(){
            return{
                status: ''
            }
        },
        methods:{
            changeStatus(){
                axios.post('contract/change-status/'+this.contractId, {status: this.status}).then(response =>{
                    this.$router.go();
                });
            },
        }
    }
</script>