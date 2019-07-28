<template>
    <div class="objectNav">
        <nav class="nav nav-tabs nav-tabs-sm">
            <li style="border-right: 1px solid silver"><router-link :to="{name:'customer-details'}" active-class="active">
                <span style="color: royalblue">Overview</span></router-link></li>
            <li style="border-right: 1px solid silver"><router-link :to="{name:'customer-contacts'}" active-class="active">
                <span style="color: royalblue">Contact</span></router-link></li>
            <li style="border-right: 1px solid silver"><router-link :to="{name:'customer-locations'}" active-class="active">
                <span style="color: royalblue">Location</span></router-link></li>
            <li style="border-right: 1px solid silver"><router-link :to="{name:'customer-contracts'}" active-class="active">
                <span style="color: royalblue">Contract</span></router-link></li>
        </nav>
    </div>
</template>