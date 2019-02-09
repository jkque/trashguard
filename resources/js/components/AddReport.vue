<template>
    <div>
        <div class="flex-parent justify-content-center" style="margin-top: 15px">
            <div class="col-4 ">
                <h3 class="text-center">Manual Report</h3>
                <p  class="text-center">Add an entry report manually.</p>
                <div class="card">
                    <div class="card-body">
                        <form @submit="addReport($event)" id="addReportForm">
                            <div class="form-group">
                                <label >Location</label>
                                <input type="text" name="location" placeholder="Enter a place.." class="form-control" value="Lorem ipsum dolor sit amet, consectetur adipiscing elit">
                                <div class="invalid-feedback">
                                    Please enter location
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="desc" placeholder="What have you seen.." class="form-control" rows="3">Etiam tempus egestas posuere. In quis nisi sit amet metus commodo bibendum. Quisque in malesuada dolor, ut convallis neque. Ut sodales, dolor tincidunt commodo hendrerit, massa eros imperdiet turpis, non consectetur arcu enim condimentum augue. Integer ut auctor nulla, non egestas ante. Phasellus ac elementum quam. Suspendisse potenti. Nulla eros dolor, rutrum a neque porta, hendrerit tempor urna. Vivamus augue odio, mollis ac augue eget, ultricies consequat felis. Nam rhoncus ex id sapien aliquam, aliquam ornare velit faucibus.</textarea>
                                <div class="invalid-feedback">
                                    Please enter description
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" value="Image" class="form-control" accept="image/*" >
                                <div class="invalid-feedback">
                                    Please enter image
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user">Reporter</label>
                                <select name="user_id" class="form-control" id="user">
                                    <option :value="user.id" v-for="(user,index) in users" :key="index">
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-add-report">Add Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                users:  [],
                paginate:   null,
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
                        toastr.success('A new entry has been added','Success!');
                    } else {
                        self.validateForm(response.data.errors);
                        toastr.error('Failed to add a new entry','Oops!');
                    }
                    $(".btn-add-report").prop("disabled",false);
                }).catch((error)=>{

                });
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