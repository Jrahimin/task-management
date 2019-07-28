<template>

    <div>
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-md-5" id="dateDiv">
                    <input type="date" v-model="date" @change="onDateChange"/>
                </div>

                <div class="col-sm-7">
                    <div id="day" v-for="event in events">
                        <h3>{{event.subject}}</h3>
                        <p>From: {{event.start_date}}</p>
                        <p>To: {{event.end_date}}</p>
                        <p>{{event.is_all_day}}</p>
                        <p>{{event.schedule}}</p>
                        <p>- {{event.customer}}</p>
                        <p> <button type="button" class="btn btn-info" @click="editEvent(event.id)">Edit</button>
                            <button type="button" class="btn btn-danger" @click="deleteEvent(event.id)">Delete</button>
                        </p>
                    </div>
                </div>
            </div>
        </form>

    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    export default {
        data(){
            return {
                date:new Date(),
                all_events:[],
                events:[],
                event :{}
            }
        },

        created(){

            this.getEvents();

        },
        methods:{
            filterEvents(date)
            {
                let today = moment(String(date)).format('YYYY-MM-DD');
                this.events=this.all_events.filter(function(event){
                    if(event.start_date==today||event.end_date==today || (event.start_date<today&&event.end_date>today))
                        return event;
                })
            },
            getEvents()
            {
                axios.get('events').then(response=>{
                    this.all_events=response.data;
                    this.filterEvents(this.date);
                });
            },
            onDateChange(){
                this.filterEvents(this.date);
            },
            editEvent(id)
            {
                this.$router.push({name:'appointment-edit',params:{id:id}});
            },
            deleteEvent(id)
            {
                axios.get('event-delete/'+id).then(response=>{
                    if(response.data.id==id)
                        this.eventDetail = false;
                    this.getEvents();
                })
            },
        }
    }
</script>

<style>
    #day{
        width: 60%;
        border-top: 1px #b6a338 solid;
        background-color: cornsilk;
        padding: 3%;
        margin: 2%;
        border-radius: 3%;
        box-shadow: 10px 10px 5px grey;
    }
    #dateDiv{
        width: 25%;
        height: 1000px;
        background-color: gainsboro;
    }
</style>