<template>
    <div>
        <div class="flex-parent" >
            <div class="col-4 report-container">
                <div class="report-search">
                    <form @submit.prevent autocomplete="off">
                        <input v-if="isLoaded" type="search" @search="searchReport()" class="form-control report-search-input" v-model="search" placeholder="Search for..">
                        <div v-else class="content-load" style="height: 37px; width: 100%; "></div>
                    </form>
                </div>
                <div class="report-list-scrollable" v-if="isLoaded">
                    <ul class="nav flex-column nav-pills report-list" id="pills-tab" role="tablist">
                        <li class="nav-item report-item " @click="selectReport(report)" v-for="(report,index) in reports" :key="index">
                            <div :class="['nav-link', {'active': (index == 0)}]" data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">
                                <h5 class="report-location clearfix"><span class="report-location-name ellipsis">{{ report.location }}</span> <span class="report-time">{{ report.created_at | standard_date }}</span></h5>
                                
                                <p class="report-details ellipsis ">{{ report.desc }}</p>
                            </div>
                        </li>
                        <li v-if="(pagination) ? (pagination.next_page_url != null) : false">
                            <button class="btn btn-outline-primary btn-block" @click="loadMore($event)" btn-loading-text="Loading more..">Load more</button>
                        </li>
                    </ul>
                </div>
                <!-- preloader -->
                <div class="report-list-scrollable" v-else>
                    <ul class="nav flex-column nav-pills report-list" id="pills-tab" role="tablist">
                        <li class="nav-item report-item">
                            <div class="nav-link"  data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">
                                <div class="content-load" style="height: 21px; width: 100%"></div>
                                <div class="content-load" style="height: 21px; width: 70%"></div>
                            </div>
                        </li>
                        <li class="nav-item report-item">
                            <div class="nav-link"  data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">
                                <div class="content-load" style="height: 21px; width: 100%"></div>
                                <div class="content-load" style="height: 21px; width: 70%"></div>
                            </div>
                        </li>
                        <li class="nav-item report-item">
                            <div class="nav-link"  data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">
                                <div class="content-load" style="height: 21px; width: 100%"></div>
                                <div class="content-load" style="height: 21px; width: 70%"></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- preloader -->

            </div>
            <div class="col-8 report-view " >
                <div v-if="selected_report || (isLoaded && !reloadReportView)">
                    <div v-if="empty">
                        <div style="height: 50vh; display: flex; align-items: center; justify-content:center;">
                            <h2 class="text-center">Sorry, no reports found</h2>
                        </div>
                    </div>
                    <div v-else>
                        <div class="report-view-action-bar" v-if="selected_report">
                            <div class="row align-items-center">
                                <div class="col-6" v-if="current_type == 'reports'">
                                    <button type="button" @click="actionButton('approve')" class="btn btn-light"><i class="ti-check"></i>&nbsp; Approve</button>
                                    <button type="button" @click="actionButton('decline')" class="btn btn-light"><i class="ti-close"></i>&nbsp; Decline</button>
                                </div>
                                <div class="col-6" v-if="current_type == 'ongoing'">
                                    <button type="button" @click="actionButton('solve')" class="btn btn-outline-success"><i class="ti-thumb-up"></i>&nbsp; Mark as solved</button>
                                </div>
                                <div class="col-6" v-if="my_type == 1 || my_type == 3">
                                    <button type="button" @click="actionButton('delete')" class="btn btn-outline-danger"><i class="ti-trash"></i>&nbsp; Delete</button>
                                </div>
                                <div class="col-6" v-if="my_type == 4">
                                    <div class="alert alert-success" role="alert">
                                        Deleted
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="clearfix">
                                        <div class="pull-right"><strong>{{ selected_report.created_at | standard_date }}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="report-view-details" v-if="selected_report">
                            <div class="flex-parent">
                                <div class="col-1">
                                    <div class="location-icon">
                                        <h2><i class="ti-location-pin"></i></h2>
                                    </div>
                                </div>
                                <div class="col-11">
                                    <h2>{{ selected_report.location }}</h2>
                                    <p v-if="selected_report.witnessed_at" style="color: #7f8c8d">Witnessed at {{ selected_report.witnessed_at | standard_date }}</p>
                                </div>
                            </div>
                            <div class="report-description">
                                <label><strong>Description</strong></label>
                                <p >{{ selected_report.desc }}</p>
                            </div>
                            <div class="report-description">
                                <label><strong>Attachment</strong></label>
                                <!-- <img src="" alt="" class="img-responsive"> -->
                                <div class="card-deck no-margin" >
		                            <div class="card my-3" @click="showImage(image.image_name)" v-for="(image,index) in selected_report.images" :key="index" style="cursor:pointer;align-items:center; justify-content: center;">
                                        <img style="display: block;" :src="image.image_name | url"  class="img-fluid img-thumbnail" alt="Responsive image">
                                    </div>
                                </div>
                            </div>
                            <div class="report-description" v-show="selected_report.latitude && selected_report.longitude">
                                <gmap-map :center="center" :zoom="12" style="width:100%;  height: 400px; margin-bottom:100px;" v-bind:options="mapStyle">
                                    <gmap-marker  :key="index" v-for="(m, index) in markers"  :position="m.position"></gmap-marker>
                                </gmap-map>
                            </div> 
                        </div>
                    </div>
                </div>
                <!-- preloader -->
                <div v-else>
                    <div class="report-view-action-bar">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <div class="content-load" style="height: 37px; width: 100px; display: inline-block;"></div>
                                <div class="content-load" style="height: 37px; width: 100px; display: inline-block;"></div>
                                <div class="content-load" style="height: 37px; width: 100px; display: inline-block;"></div>

                            </div>
                            <div class="col-6">
                                <div class="clearfix">
                                    <div class="pull-right content-load" style="height: 37px; width: 100px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="report-view-details">
                        <div class="flex-parent">
                            <div class="col-1">
                                <div class="location-icon">
                                    <div class="content-load circle" style="height: 40px; width: 40px;"></div>
                                </div>
                            </div>
                            <div class="col-11">
                                <div class="content-load" style="height: 30px; width: 50%"></div>
                                <div class="content-load" style="height: 30px; width: 30%"></div>
                            </div>
                        </div>
                        <div class="report-description">
                            <div class="content-load" style="height: 25px; width: 80%; margin-bottom: 10px;"></div>
                            <div class="content-load" style="height: 25px; width: 100%; margin-bottom: 10px;"></div>
                            <div class="content-load" style="height: 25px; width: 100%; margin-bottom: 10px;"></div>
                            <div class="content-load" style="height: 25px; width: 60%; margin-bottom: 10px;"></div>
                        </div>
                    </div>
                </div>
                <!-- preloader -->
            </div>
        </div>
        <div class="modal fade" id="show-image" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img v-if="image_path" style="margin: 0 auto; display: block;" :src="image_path | url" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["type"],
        data(){
            return {
                reports:        [],
                selected_report:    null,
                pagination:     null,
                isLoaded:       false,
                reloadReportView: false,
                search:         "",
                current_type:   "reports",
                my_type:           0,
                empty:          false,
                image_path:     null,
                center: { lat: 10.3157, lng: 123.8854 },
                markers: [],
                mapStyle: {scrollwheel: false, navigationControl: false, mapTypeControl: false, scaleControl: false, draggable: false,zoom: 13,styles: [{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.province","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.attraction","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.attraction","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"poi.business","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.business","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"poi.government","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.government","elementType":"labels.icon","stylers":[{"color":"#ff0000"}]},{"featureType":"poi.medical","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.medical","elementType":"labels.icon","stylers":[{"color":"#ff0000"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.park","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"poi.place_of_worship","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"poi.school","elementType":"labels.text.fill","stylers":[{"color":"#080808"}]},{"featureType":"poi.sports_complex","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#ffcf15"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffe891"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"transit.line","elementType":"labels.icon","stylers":[{"color":"#000000"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"transit.station.airport","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"transit.station.bus","elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"featureType":"transit.station.rail","elementType":"labels.text.fill","stylers":[{"color":"#ff0000"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#2d2d2d"}]}]}

            }
        },
        mounted(){
            let self = this;
            setTimeout(() => { 
                self.isLoaded = true; 
                
            },100);

        },
        created(){
            let self = this;
            if(self.type) { 
                self.current_type = self.type; 
                self.my_type = self.interpretType(this.type); 
            }
            this.getReports(null,(response)=>{
                if(self.selected_report){
                    if(self.selected_report.longitude && self.selected_report.latitude){
                        self.center = { lat: parseFloat(self.selected_report.latitude), lng: parseFloat(self.selected_report.longitude) };
                        self.markers.push(new google.maps.Marker({
                            position: {
                                lat: parseFloat(self.selected_report.latitude),
                                lng: parseFloat(self.selected_report.longitude)
                            },
                        }));
                    }
                }
            });
        },
        methods: {
            interpretType(type){
                switch(type){
                    case "archive": return 4; break;
                    case "declined": return 3; break;
                    case "ongoing": return 2; break;
                    case "solved": return 1; break;
                    case "reports": return 0; break;
                }
            },
            loadMore(event){
                $(event.target).button('loading');
                $(event.target).prop('disabled',true);
                this.getReports(this.pagination.next_page_url,(response)=>{
                    $(event.target).button('reset');
                    $(event.target).prop('disabled',false);
                },true);
            },
            searchReport(){
                let self = this;
                self.isLoaded = false;
                self.getReports(null,(response)=>{ 
                    self.isLoaded = true;                
                });
            },
            selectReport(report){
                this.selected_report = report;
                this.markers = [];
                if(report.latitude && report.longitude){
                    this.center = { lat: parseFloat(report.latitude), lng: parseFloat(report.longitude) };
                    this.markers.push(new google.maps.Marker({
                        position: {
                            lat: parseFloat(report.latitude),
                            lng: parseFloat(report.longitude)
                        },
                    }));
                }
                $(".report-view-details").scrollTop = 0;
            },
            showImage(path){
                this.image_path = path;
                $('#show-image').modal("show");
            },
            actionButton(action){
                let self = this;
                swal({
                    title: "Are you sure?",
                    text: "This report will be "+action+"d",
                    icon: "warning",
                    buttons: [true, action.charAt(0).toUpperCase() + action.slice(1)],
                    dangerMode: (action != "approve"),
                })
                .then((willAction) => {
                    if(willAction){
                        self.isLoaded = false;
                        axios.post('/report/'+action,{ id: self.selected_report.id }).then((response) => {
                            if(response.data.success){
                                toastr.success('Successfully '+action+'d a report','Success!');
                                self.getReports(null,(response)=>{ 
                                    self.isLoaded = true;
                                });
                            }
                        }).catch((error)=> {
                            console.log(error);
                        });
                    }
                });
            },
            getReports(url = null, callable = null, paginate = false){
                let self = this; url = (url)? url : '/getReports';
                self.selected_report = null;
                axios.get(url,{params:{search:self.search,type:self.my_type}})
                .then((response)=>{
                    self.reports = (paginate == false) ? response.data.data : $.merge(self.reports,response.data.data);
                    if(self.reports.length > 0){
                        self.selected_report = self.reports[0];
                        self.empty = false;
                    } else {
                        self.empty = true;
                    }
                    self.pagination = response.data;
                    if(callable) { callable(response); }
                }).catch((error)=>{
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped lang="scss">

    .report-container,
    .report-view {
        height: calc(100vh - 56px);
        padding: 25px;
    }
    .report-container {
        background-color: rgba(0,0,0,0.02);
        .report-search {
            padding-bottom: 20px;
            .report-search-input {
                background-color: rgba(0,0,0,0.075);
                color: #000;
                border: none;
                &:focus {
                    border: none;
                    box-shadow: none;
                }
            }
        }
        .report-list-scrollable {
            height: calc(100vh - 138px);
            overflow: auto;
        }
        .report-list {
            .report-item {
                white-space: nowrap;
                width: 100%;
                text-overflow: ellipsis;
                overflow: hidden;
                display: block;
                cursor: pointer;
                margin-bottom: 10px;
                // padding-right: 10px;

                .report-location {
                    margin-bottom: 3px;
                    .report-location-name {
                        display: block;
                        float: left;
                        font-weight: 600;
                        width: 54%;
                    }
                    .report-time {
                        display: block;
                        font-size: 13px;
                        float: right;
                    }
                }
                .report-details {
                    margin-bottom: 0px;
                    color: #9f9f9f;
                }
                & > div {
                    padding: 15px;
                    &:hover {
                        background-color: rgba(0,0,0,0.03);
                    }
                    &.active {
                        background-color: #f8d13a;
                        .report-details {
                            color: #fff;
                        }
                    }
                }
            }
        }
    }

    .report-view {
        background-color: #fff;
        .report-view-action-bar {
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.075);
        }
        .report-view-details {
            padding-top: 20px;
            height: calc(100vh - 139px);
            overflow: auto;
            .location-icon {
                margin-right: 15px;
            }
        }
        .report-description {
            margin-top: 10px;
        }
    }

    .report-status {
        padding: 10px 25px;
        border-radius: 30px;
        display: inline-block;
    }
</style>