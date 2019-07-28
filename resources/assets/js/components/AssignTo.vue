<template>
    <div>
        <form class="form-horizontal mt-4" @submit.prevent="assignUsers">
            <div class="form-group">
                <label class="control-label col-md-2">Assign To</label>
                <div class=" form-group col-md-10">
                    <select multiple v-model.number="assigned_to.user_ids" class="form-control">
                        <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                    </select>
                </div>

            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" value="0" v-model.number="assigned_to.is_scheduled">Unscheduled</label>
                    <label class="radio-inline"><input type="radio" value="1" v-model.number="assigned_to.is_scheduled">Scheduled</label>
                </div>
            </div>
            <div class="form-group" v-if="assigned_to.is_scheduled==1">
                <div class="form-group row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4">start</label>
                            <div class="col-md-6">
                                <input type="date" v-model="assigned_to.start_date"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" v-if="assigned_to.is_all_day!=1">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="time" v-model="assigned_to.start_time"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4">end</label>
                            <div class="col-md-6">
                                <input type="date" v-model="assigned_to.end_date"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" v-if="assigned_to.is_all_day!=1">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="time" v-model="assigned_to.end_time"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="checkbox pull-right"><input type="checkbox" value="1" v-model.number="assigned_to.is_all_day"/>All day</label>
                            </div>
                        </div>
                    </div>


                </div>

            </div>

            <div class="form-group" style="background-color: #83a9cc; padding: 1%; border-radius: 2%;">
                <div class="col-md-12">
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>

</template>

<script>
    import axios from 'axios';
    export default {
        data(){
            return {
                assigned_to:{
                   user_ids:[],
                    is_scheduled:'',
                    start_date:'',
                    start_time:'',
                    end_date:'',
                    end_time:'',
                    is_all_day:'',
                    comment:''
                },
                users:[],
                workOrder_id:this.$route.params.id
            }
        },
        created(){
            axios.get('user/all').then(response=>{
                this.users=response.data;
            })
        },
        methods:{
            assignUsers(){
                axios.post('workOrder-assignment/'+this.workOrder_id,this.assigned_to).then(response=>{
                    this.$router.push({name:'workorder-all'})
                })
            }
        }
    }

</script>

<style>

</style>