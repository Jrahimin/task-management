<template>
    <div>

        <div class="filter-box">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button @click="toAppointment" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Appointment</span></button>
                </div>
            </div>
        </div>

        <div class="panel panel-body">
        <full-calendar :events="fcEvents" locale="en" @dayClick="fixAppointment" @eventClick="showDetail">

        </full-calendar>
        <div id="modal" slot name="body-card" v-if="eventDetail" class="modal-content" :style="{width: 20+'%', height: 30+'%' ,position: 'absolute',
              left:pos.ClientX+'px',
                top:pos.ClientY+'px'}" >

            <slot name="fc-body-card" >
                <div class="modal-header">
                    <h4 class="modal-title">{{event.subject}}</h4>
                </div>
                <div class="modal-body">
                    <p>{{event.start_date_time}}</p>
                    <p>{{event.end_date_time}}</p>
                    <p>{{event.notes}}</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" @click="hideModal">Close</button>
                    <button type="button" class="btn btn-info" @click="editEvent(event.id)">Edit</button>
                </div>
            </slot>

        </div>
            <div v-if="newAppointment" class="modal-content" style="width:auto;height:auto">

                    <add-appointment></add-appointment>

                <button type="button" class="btn btn-default"  @click="hideModal">Close</button>
            </div>

        </div>
    </div>
</template>
<script>
    import fullCalendar from 'vue-fullcalendar';
    import AddAppointment from './AddAppointment.vue';
    import moment from 'moment';
    import axios from 'axios';
export default {
   data () {
    return {
    fcEvents : [],
        event:{
            'title':'',
            'start':'',
            'end':'',
            'notes':'',
            'id':'',
        },
        pos:'',
        newAppointment:false,
        eventDetail:false,
   }
   },
    created(){
       axios.get('events').then(response=>{
           let events=[];
           response.data.forEach(function(val)
           {
               let customer_id=val.customer_id;

               let event={
                   'title':val.subject,
                   'start':val.start_date_time,
                   'end':val.end_date_time,
                   'notes':val.notes,
                   'id':val.id,
               }
               events.push(event);
           });
           this.fcEvents=events;

       })
    },
   components : {
    fullCalendar,
       AddAppointment
  },
    methods:{
        toAppointment() {
            this.$router.push({name: 'add-appointment'});
    },
        fixAppointment(day)
        {
           // let date=moment(String(day)).format('YYYY-MM-DD');
           this.newAppointment=true;
          // this.$router.push({name:'add-appointment',  params:{date:date}});
        },
        showDetail(event,pos)
        {
            axios.get('event/'+event.id).then(response=>{
                this.event=response.data;

                this.pos=pos;
                this.eventDetail=true;
                document.getElementById('modal').style='display:block;height:30%;width:20%;position: absolute; left:'+this.pos.ScreenX+'px; top:'+this.pos.ScreenY+'px';
            }

           )
        },
        hideModal()
        {
            this.newAppointment=false;
            this.eventDetail=false;

        },
        editEvent(id)
        {
            this.$router.push({name:'appointment-edit',params:{id:id}});
        }
    },
}

</script>
<style>
    .modal-content {
        position: absolute;
        right: 80px;
        bottom: 0;
        z-index: 10040;
        overflow: auto;
        overflow-y: auto;
    }
</style>
