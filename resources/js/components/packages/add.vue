<template>
<div>
    <div  class="add_button ml-10 mb-5">
                                                <a  href="#" data-toggle="modal" data-target="#savePack" class="btn_1">Add Package</a>
                                              
                                            </div>
                                             <div  class="container-fluid plr_30" v-if="errors.length">
                        <div  class="row justify-content-center">
                             <div  class="col-lg-12">
                                 <div class="alert alert-danger" role="alert" v-for="error in errors" :key="error.id">
                                    {{error}}
                                </div>
                             </div>    
                        </div>
                    </div>
   <table class="table table-bordered">
  <thead>
    <tr>
      
      <th scope="col">Name</th>
      <th scope="col">Time</th>
      <th scope="col">Value</th>
      <th scope="col">Discription</th>
      <th scope="col">Active</th>
      <th scope="col">Actions</th>


    </tr>
  </thead>
  <tbody>
    <tr v-for="packagee in packages" :key="packagee.id" >
      <td>{{packagee.name}}</td>
      <td>{{packagee.time}} Months</td>
      <td>{{packagee.value}} EGP</td>
      <td>{{packagee.description}}</td>
      <td>{{packagee.enabled}}</td>


      <td>
             <a  href="#" data-toggle="modal" data-target="#edit_package" v-on:click="ShowPackage(packagee.id)" class="white_btn">Edit</a>
             <a  href="#" v-on:click="DeletePackage(semester.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 

      </td>
      
    </tr>
 
  </tbody>
</table>
   <div  id="edit_package" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                   <label>Name</label> 
                                                    <input  v-model="showName" class="input_form form-control">
                                                    <label>value</label> 
                                                    <input  v-model="showValue" class="input_form form-control">
                                                    <label>Description</label> 
                                                    <input  v-model="showDescription" class="input_form form-control">
                                                     <label>Time In months</label> 
                                                    <input  v-model="showTime" class="input_form form-control"> 
                                                    <label>Active</label> 
                                                    <input  v-model="showActive" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="UpdateSemester" data-dismiss="modal" aria-label="Close"  type="submit" class="btn_1 m-0">Update</button>
                                            </div>
                                  </form>
                              </div>
                        </div>
                    </div>
                    <div  id="savePack" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                    <label>Name</label> 
                                                    <input  v-model="name" class="input_form form-control">
                                                    <label>value</label> 
                                                    <input  v-model="value" class="input_form form-control">
                                                    <label>Description</label> 
                                                    <input  v-model="description" class="input_form form-control">
                                                     <label>Time In months</label> 
                                                    <input  v-model="time" class="input_form form-control"> 
                                                    <label>Active</label> 
                                                    <input  v-model="active" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="savePackage" type="submit" data-dismiss="modal" aria-label="Close"  class="btn_1 m-0">Save</button>
                                            </div>
                                  </form>
                              </div>
                        </div>
                    </div>
                  </div>
</template>

<script>
import axios from '../../axios'
import '../lesson.css'


    export default {
        data() {
            return {
                packages: [],
                ShowEnSemName:'',
                ShowArSemName:'',
                packageID:'',
                name:'',
                value:'',
                time:'',
                description:'',
                active:'',
                ShowName:'',
                ShowValue:'',
                ShowTime:'',
                ShowDescription:'',
                ShowActive:'',
                errors:[]
               
            }
        },
        methods:{
            getPackages(){
                axios.get('/api/v1/get-packages').then(res=>{
                    this.packages = res.data.data
                })
            },
            ShowPackage(id){
              this.packageID = id
              axios.get('/api/v1/package/'+id).then(res=>{
                  
                this.ShowName= res.data.data.name
                this.ShowValue= res.data.data.value
                this.ShowTime= res.data.data.time
                this.ShowDescription= res.data.data.description
                this.ShowActive= res.data.data.enabled

                

              })
            },
            UpdateSemester(){
                if(!this.ShowArSemName){
        this.errors.push("Arabic Name is Required in Edit Semester.");
      }
      if(!this.ShowEnSemName){
        this.errors.push("English Name is Required in Edit Semester.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{
              axios.post('/api/v1/semester/edit/'+this.packageID ,{
                en_name : this.ShowEnSemName,
                ar_name : this.ShowArSemName

              }).then(res=>{
                 this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Updated Succesfully',
            duration: 9000,
            dismissible: true
          });
           this.reload()
              })

      }
            },
            DeleteSemester(id){
              this.$confirm("Are you sure?").then(() => {

              axios.delete('/api/v1/semester/remove/'+id).then(res=>{

                    this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Deleted Succesfully',
            duration: 9000,
            dismissible: true
          });

              })
            }

              )},
              savePackage(){
                if(!this.name){
        this.errors.push(" Name is Required in Add Package.");
      }
    
   
      else{
                axios.post('/api/v1/create/package',{
                  name:this.name,
                  value:this.value,
                  description:this.description,
                  time:this.time,
                  enabled:this.active,
                 

                 
                }).then(res=>{
                  
        this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Added Succesfully',
            duration: 9000,
            dismissible: true
          });
             this.getPackages()
                })
      }
              },

   reload(){
       window.location.reload()
   }
      
            
        },
        
        mounted(){
            this.getPackages()
            
        }
    }
</script>