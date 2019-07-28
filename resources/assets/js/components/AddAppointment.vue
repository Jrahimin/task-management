<template>

        <div class="container" style="width: 100%">
            <fieldset>
                <legend><h3>New Appointment</h3></legend>
                <form class="form-horizontal" @submit.prevent="addAppointment">
                    <div class="form-group">
                        <label class="control-label col-md-2">Subject</label>
                        <div class="col-md-6">
                            <input class="form-control" v-model="appointment.subject"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Start</label>
                        <div class="col-md-2">
                            <input class="form-control" type="date" v-model="appointment.start_date"/>
                        </div>

                        <div class="col-md-2">
                            <input class="form-control" v-if="appointment.is_all_day!=1"
                                   type="time" v-model="appointment.start_time" step="900"/>
                        </div>

                        <div class="col-md-2">
                            <label class="checkbox"><input type="checkbox" value="1" v-model.number="appointment.is_all_day"/>All day</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">End</label>
                        <div class="col-md-2">
                            <input  type ="date" class="form-control" v-model="appointment.end_date"/>
                        </div>

                        <div class="col-md-2">
                            <input class="form-control" v-if="appointment.is_all_day!=1" v-model="appointment.end_time" type="time" step="900"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Notes</label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="3" v-model="appointment.notes"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2"> Assign To </label>
                        <div class="col-md-6">
                            <select multiple v-model.number="appointment.user_ids"  class="form-control">
                                <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2"> Customer </label>
                        <div class="col-md-6">
                            <select v-model.number="appointment.customer_id"  class="form-control">
                                <option v-for="customer in customers" :value="customer.id" >{{customer.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" >Status</label>
                        <div class="checkbox">
                            <label class="control-label"><input type="checkbox" value="1"
                                                                v-model="appointment.is_recurring" @click="isRecurring" />Appointment is Recurring</label>
                        </div>
                    </div>

                    <div class="form-group" v-if="appointment.is_recurring==1">
                        <label class="control-label col-md-2 radio-inline">
                            <input type="radio" value="1" v-model="appointment_recurrence"
                                   @click="appointment.part_of_month='', appointment.week_in_month=''"/>Every</label>

                        <div class="col-md-2">
                            <input :disabled="appointment_recurrence!=1" class="form-control" v-model.number="appointment.interval" />
                        </div>

                        <div class="col-md-2">
                            <select :disabled="appointment_recurrence!=1" class="form-control" v-model="appointment.frequency"
                                    @change="appointment.days_in_week=[]">
                                <option value="1">Day(s)</option>
                                <option value="2">Week(s)</option>
                                <option value="3">Month(s)</option>
                                <option value="4">Year(s)</option>
                            </select>
                        </div>

                        <div class="col-md-6" v-if="appointment.frequency==2">
                            <label class="control-label col-md-2">On</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Sun" v-model="appointment.days_in_week">Sun</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Mon" v-model="appointment.days_in_week">Mon</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Tue" v-model="appointment.days_in_week">Tue</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Wed" v-model="appointment.days_in_week">Wed</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Thu" v-model="appointment.days_in_week">Thu</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Fri" v-model="appointment.days_in_week">Fri</label>
                            <label class="checkbox-inline"><input type="checkbox" value="Sat" v-model="appointment.days_in_week">Sat</label>
                        </div>

                    </div>

                    <div class="form-group" v-if="appointment.is_recurring==1">
                        <label class="control-label col-md-2 radio-inline">
                            <input type="radio" value="2" v-model="appointment_recurrence"
                                   @click="appointment.frequency='', appointment.days_in_week=[]"/>The</label>

                        <div class="col-md-2">
                            <select :disabled="appointment_recurrence!=2" class="form-control" v-model.number="appointment.part_of_month">
                                <option value="first">First</option>
                                <option value="second">Second</option>
                                <option value="third">Third</option>
                                <option value="fourth">Fourth</option>
                                <option value="last">Last</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select :disabled="appointment_recurrence!=2" class="form-control" v-model.number="appointment.day_in_month">
                                <option value="sun">Sunday</option>
                                <option value="mon">Monday</option>
                                <option value="tue">Tuesday</option>
                                <option value="wed">Wednesday</option>
                                <option value="thu">Thursday</option>
                                <option value="fri">Friday</option>
                                <option value="sat">Saturday</option>
                            </select>
                        </div>

                        <label class="col-md-1">of every</label>

                        <div class="col-md-1">
                            <input :disabled="appointment_recurrence!=2" class="form-control" v-model="appointment.interval"/>
                        </div>

                        <label class="col-md-1">month(s)</label>
                    </div>

                    <div v-if="appointment.is_recurring==1">
                        <div class="form-group">
                            <label class="control-label radio-inline col-md-2"><input type="radio" value="1" v-model="recurrence_end"
                                                                                      @click="appointment.recurring_end_date=''"/>Ends after</label>
                            <div class="col-md-1">
                                <input :disabled="recurrence_end!=1" class="form-control" v-model="appointment.end_occurance"/>
                            </div>

                            <label class="control-label col-md-1">occurances</label>
                        </div>

                        <div class="form-group">

                            <label class="control-label radio-inline col-md-2"><input type="radio" value="2" v-model="recurrence_end"
                                                                                      @click="appointment.end_occurance=''"/>Ends on</label>
                            <div class="col-md-2">
                                <input :disabled="recurrence_end!=2" type="date" class="form-control" v-model="appointment.recurring_end_date"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group alert alert-danger" v-if="errors!=''">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>

                    <div class="form-group" style="background-color: #83a9cc; padding: 1%; border-radius: 2%;">
                        <div class="col-md-12">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>

                </form>
            </fieldset>
        </div>


</template>

<script>
    import axios from 'axios'

    export default {
        data(){
            return{
                appointment:{
                    subject: '',start_date:this.date,start_time: '',end_date:this.date, end_time: '',is_all_day: '',notes: '',
                    work_order_id: '',customer_id: '', is_recurring: '',parent_id: '',frequency: '',no_of_occurance: '',
                    end_occurance: '',interval: '', days_in_week: [], day_in_month: '',
                    part_of_month: '',recurring_end_date: '', user_ids: [],
                },
                appointment_recurrence:'', recurrence_end: '',
                users: [], customers: [], errors: [],
            }
        },
        props:{date:{
            default:''
        }},
        methods:{
            addAppointment(){
                axios.post('appointment/add', this.appointment).then(response =>{
                    this.errors = response.data.message
                    console.log(this.errors)
                    if(this.errors==undefined){
                        this.$router.push({name:'calendar-view'})
                    }
                });
            },
          isRecurring(){
              if(this.appointment.is_recurring!=1)
              {
                  this.appointment.frequency = '';
                  this.appointment.end_occurance = '';
                  this.appointment.interval = '';
                  this.appointment.days_in_week = [];
                  this.appointment.day_in_month = '';
                  this.appointment.part_of_month = '';
                  this.appointment.recurring_end_date = '';
                  this.appointment_recurrence = '';
                  this.recurrence_end = '';
              }
          }
        },
        created(){
            axios.get('user/all').then(response => {
                this.users = response.data;
            })
            axios.get('customer/all').then(response => {
                this.customers = response.data;
            })
        },
    }

</script>

<style>

</style>