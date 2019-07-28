<template>
<div>


    <div class='calendar'>
        <div class="header">
            <a class="arrow" @click="moveToPrevious">Previous</a>
            <a class="arrow" @click="moveToNext">Next</a>
        </div>

        <div class='weekdays'>
            <div class='weekday' v-for='weekday in weekdays'>
                {{ weekday.label }}
            </div>
        </div>
        <div class='week' >
            <div class='day' :class='{ today: day.isToday }' v-for='day in week' @click="addAppointment(day.date)">
                {{ day.label }}
                <div class="box-info" v-for="event in day.events" @click=" showEventDetails" :id="event.id">
                    <ul id="dayUl" style="display: block; width: 100%">
                        <li><b>{{event.subject}}</b></li>
                        <li>{{event.customer}}</li>
                        <li>{{event.notes}}</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <div v-if="newAppointment" class="modal-content" >

        <add-appointment :date="currentDate" ></add-appointment>

        <button type="button" class="btn btn-default"  @click="hideModal">Close</button>

    </div>
    <div v-if="eventDetail" class="modal-content"  >

        <div class="modal-header">
            <h4 class="modal-title">{{event.subject}}</h4>
        </div>
        <div class="modal-body">
            <p>{{event.start_date}}</p>
            <p>{{event.end_date}}</p>
            <p>{{event.schedule}}</p>
            <p>{{event.is_all_day}}</p>
            <p>{{event.notes}}</p>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" @click="hideModal">Close</button>
            <button type="button" class="btn btn-info" @click="editEvent(event.id)">Edit</button>
            <button type="button" class="btn btn-danger" @click="deleteEvent(event.id)">Delete</button>
        </div>
    </div>
</div>


</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import AddAppointment from './AddAppointment.vue';

    var daysInMonths = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var weekdayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday','Sunday'];
    var monthLength = 0;
    var monthCasing = 'title';
    var weekdayLength = 3;
    var weekdayCasing = 'title';

    // Helper function for label transformation
    var transformLabel = (label, length, casing) => {
        label = length <= 0 ? label : label.substring(0, length);
        if (casing.toLowerCase() === 'lower') return label.toLowerCase();
        if (casing.toLowerCase() === 'upper') return label.toUpperCase();
        return label;
    };

    export default {
        data() {
            return {
                date:new Date(),
                events:[],
                newAppointment:false,
                currentDate:'',
                event:{},
                eventDetail:false,


            }
        },
        computed : {

            monthIndex() {
                return this.date.getMonth();
            },
            months() {
                return monthLabels.map((ml, i) => ({
                    label: transformLabel(ml, monthLength, monthCasing),
                    number: i + 1,
                }));
            },
            // State for weekday header (no dependencies yet...)
            weekdays() {
                return weekdayLabels.map((wl, i) => {
                    return {
                        label: transformLabel(wl, weekdayLength, weekdayCasing),
                        number: i + 1,
                    };
                });
            },
            getMonday() {

                    let day = this.date.getDay();
                    let diff = this.date.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
                    return new Date(this.date.setDate(diff));

            },


            week() {
               // console.log(this.date);
                let week = [];
                let monday=this.getMonday.getDate();
                let friday=monday+6;
                let monthEnd=false;
                let counter=0;
                if(friday>daysInMonths[this.monthIndex])
                {
                    friday=daysInMonths[this.monthIndex];
                    monthEnd=true;
                }

                // Cycle through each weekday
                for (let d = monday; d <= friday; d++) {

                    counter++;
                    let currentMonth=this.date.getMonth()+1;
                    // Append day info for the current week
                    week.push({
                        label: d.toString()+"  / "+currentMonth,
                        number: d,
                        events: this.getEvents(new Date(this.date.getFullYear(), this.monthIndex, d)),
                        date: new Date(this.date.getFullYear(), this.monthIndex, d)


                    });
                }
                if(monthEnd)
                {
                    let endCounter=7-counter;
                    let currentMonth=this.date.getMonth()+2;
                    for (let d = 1; d <=endCounter; d++) {
                        // Append day info for the current week
                        week.push({
                            label: d.toString()+"  / "+currentMonth,
                            number: d,
                            events: this.getEvents(new Date(this.date.getFullYear(), this.monthIndex, d)),
                            date: new Date(this.date.getFullYear(), this.monthIndex, d)


                        });
                    }
                }
                return week;

            }
            },
            methods: {
                getEvents(date) {
                    let todaysEvents = [];
                    let today = moment(String(date)).format('YYYY-MM-DD');

                    todaysEvents = this.events.filter(function (event) {
                        if (event.start_date == today || event.end_date == today || (event.start_date < today && event.end_date > today))
                            return event;
                    })

                    return todaysEvents;
                },


                addAppointment(date) {
                    this.currentDate = moment(String(date)).format('YYYY-MM-DD');
                    this.newAppointment = true;
                },
                hideModal() {
                    this.newAppointment = false;
                    this.eventDetail = false;

                },


                showEventDetails(e) {
                    e.stopPropagation();
                    let id = (e.currentTarget.id);
                    axios.get('event/' + id).then(response => {
                        this.event = response.data;
                    })
                    this.eventDetail = true;
                },
                 editEvent(id) {
                    this.$router.push({name: 'appointment-edit', params: {id: id}});
                },
                toAppointment() {
                    this.$router.push({name: 'add-appointment'});
                },
                moveToPrevious()
                {
                    this.date= new  Date(this.date.setDate(this.date.getDate()-7));
                },
                moveToNext()
                {
                    this.date= new  Date( this.date.setDate(this.date.getDate()+7));
                },

                deleteEvent(id)
                {
                   axios.get('event-delete/'+id).then(response=>{
                       if(response.data.id==id)
                           this.eventDetail = false;
                            this.getAllEvents();
                   })
                },
                getAllEvents()
                {
                    axios.get('events').then(response => {
                        this.events = response.data;
                    })
                }
            },
            components: {
                AddAppointment
            },
            created() {
               this.getAllEvents();
            }

    }

</script>

<style>


    table{
        table-layout: fixed;
    }
    .calendar{
        display: flex;
        flex-direction: column;
    }
    .header {
        display: flex;
        justify-content: stretch;
        align-items: center;
        color: white;
        padding: 0.5rem 1rem;
        border-width: 1px;
        border-style: solid;
        border-color: #aaaaaa;
        background-color: #133d55;
    }
    .arrow{

        padding: 0 0.4em 0.2em 0.4em;
        font-size: 1.8rem;
        font-weight: 500;
        user-select: none;
        flex-grow: 0;

    }
    .title
    {
        flex-grow: 1;
        font-size: 1.2rem;
        text-align: center;
    }
    .weekdays
    {
        display: flex;
        flex: auto;
    }
    .weekday
    {
        width: 14.2857%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.4rem 0;
        color: #7a7a7a;
        border-width: 1px;
        border-style:  solid;
        border-color:  #aaaaaa;
        background-color:#eaeaea;
    }
    .week{
        display: flex;
    }
    .day{
        width: 14.2857%;
        height: auto;
        display: block;
        justify-content: center;
        align-items: center;
        color: #3a3a3a;
        background-color: whitesmoke;
        border: solid 1px #aaaaaa;

    }
    .today{
        font-weight: 500;
        color: black;
        background-color: #b2dba1;

    }

    #dayUl{
        border-top: 1px #b6a338 solid;
        background-color: lightgoldenrodyellow;
        padding: 3%;
        margin: 2%;
    }
</style>