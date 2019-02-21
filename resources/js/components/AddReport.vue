<template>
    <div>
        <div style="height: calc(100vh - 56px); padding-bottom: 15%; overflow: auto;">
            <div class="flex-parent justify-content-center" style="margin-top: 25px;">
                <div class="col-6 ">
                    <h3 class="text-center">Manual Report</h3>
                    <p  class="text-center">Add an entry report manually.</p>
                    <div class="card">
                        <div class="card-body">
                            <form @submit="addReport($event)" id="addReportForm">
                                <div class="form-group">
                                    <label >Location</label>
                                    <input type="text" name="location" placeholder="Enter a place.." class="form-control" >
                                    <div class="invalid-feedback">
                                        Please enter location
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="desc" placeholder="Please describe it.." class="form-control" rows="3"></textarea>
                                    <div class="invalid-feedback">
                                        Please enter description
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="images[]" @change="selectedFiles($event)" class="custom-file-input" accept="image/*" multiple>
                                        <label class="custom-file-label" for="customFile">{{ (files_selected > 0) ? files_selected+((files_selected > 1)?" files":" file")+" selected":"Choose file" }}</label>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter image
                                    </div>
                                </div>
                                <div class="form-group d-none">
                                    <label for="user">Reporter</label>
                                    <select name="user_id" class="form-control" id="user">
                                        <option :value="user.id" v-for="(user,index) in users" :key="index">
                                            {{ user.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="user">Date witnessed</label>
                                    <date-picker name="witnessed_at" v-model="date"></date-picker>
                                </div>
                                <button type="submit" class="btn btn-primary btn-add-report">Add Report</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import datePicker from 'vue-bootstrap-datetimepicker';
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
    export default {
        components: {
            datePicker,
        },
        data(){
            return {
                users:  [],
                paginate:   null,
                date: new Date(),
                options: {
                format: 'DD/MM/YYYY',
                    useCurrent: false,
                },
                files_selected: 0,
            }
        },
        created(){
            this.getUsers();
        },
        methods: {
            addReport(event){
                let self = this;
                event.preventDefault();
                $(".btn-add-report").prop("disabled",true);
                let formData = new FormData($("#addReportForm")[0]);
                axios.post('/addReport',formData)
                .then((response)=>{
                    if(response.data.success){
                        $("#addReportForm")[0].reset();
                        this.date = new Date();
                        toastr.success('A new entry has been added','Success!');
                    } else {
                        self.validateForm(response.data.errors);
                        toastr.error('Failed to add a new entry','Oops!');
                    }
                    $(".btn-add-report").prop("disabled",false);
                }).catch((error)=>{

                });
            },
            selectedFiles(event){
                var files = $(event.target)[0].files;
                this.files_selected = files.length;
            },
            validateForm(errors){
                $(".form-control").removeClass("invalid");
                $(".form-group").removeClass("has-error");
                $.map(errors,(error,index) => {
                    console.log(error);
                    $("[name='"+index+"']").addClass("invalid");
                    $("[name='"+index+"']").parent().addClass("has-error");
                });
            },
            getUsers(){
                let self = this;
                axios.get('/getUsers')
                .then((response)=>{
                    self.users = response.data.data;
                    self.paginate = response.data;
                }).catch((error)=>{

                });
            }
        }
    }
</script>

<style scoped lang="scss">
</style>