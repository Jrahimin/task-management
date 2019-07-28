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
                            <option value="1">New</option>
                            <option value="2">Assigned</option>
                            <option value="3">Postponed</option>
                            <option value="4">Completed</option>
                        </select>
                    </div>
                </div>


                <div v-if="status==2" class="form-group">
                    <label class="control-label col-md-2">Assign To</label>
                    <div class="col-md-10">
                        <select multiple v-model.number="user_ids" class="form-control">
                            <option v-for="user in users" :value="user.id" >{{user.username}}</option>
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
        props: ['workOrderId', 'heading'],
        created() {
            axios.get('work-order/'+this.workOrderId).then(response => {
                this.status = response.data.status;
            });
            axios.get('user/all').then(response => {
                this.users = response.data;

            })
        },
        data(){
            return{
                status: '',
                user_ids:[],
                users:[]
            }
        },
        methods:{
            changeStatus(){
                axios.post('work-order/change-status/'+this.workOrderId, {status: this.status,users:this.user_ids}).then(response =>{
                    this.$router.go();
                });
            },
        }
    }
</script>