<template>
<div>
    <div  class="add_button ml-10 mb-5">
                                                <a  href="#" data-toggle="modal" data-target="#saveSem" class="btn_1">Add Semester</a>
                                              
                                            </div>
   <table class="table table-bordered">
  <thead>
    <tr>
      
      <th scope="col">Arabic Name</th>
      <th scope="col">English Name</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="semester in semesters" :key="semester.id" >
      <td>{{semester.ar_name}}</td>
      <td>{{semester.en_name}}</td>
      <td>
             <a  href="#" data-toggle="modal" data-target="#edit_semester" v-on:click="ShowSemester(semester.id)" class="white_btn">Edit</a>
             <a  href="#" v-on:click="DeleteSemester(semester.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 

      </td>
      
    </tr>
 
  </tbody>
</table>
   <div  id="edit_semester" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                    <label>English Semester Name</label> 
                                                    <input  v-model="ShowEnSemName" class="input_form form-control">
                                                    <label>Arabic Semester Name</label> 
                                                    <input  v-model="ShowArSemName" class="input_form form-control">

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
                    <div  id="saveSem" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                    <label>English Semester Name</label> 
                                                    <input  v-model="EnSemName" class="input_form form-control">
                                                    <label>Arabic Semester Name</label> 
                                                    <input  v-model="ArSemName" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="saveSemester" type="submit" data-dismiss="modal" aria-label="Close"  class="btn_1 m-0">Save</button>
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
                semesters: [],
                ShowEnSemName:'',
                ShowArSemName:'',
                semseterId:'',
                EnSemName:'',
                ArSemName:''

               
            }
        },
        methods:{
            getSemesters(){
                axios.get('/api/v1/get-semesters').then(res=>{
                    this.semesters = res.data
                })
            },
            ShowSemester(id){
              this.semseterId = id
              axios.get('/api/v1/semester/'+id).then(res=>{
                this.ShowEnSemName = res.data.en_name
                this.ShowArSemName = res.data.ar_name

              })
            },
            UpdateSemester(){
              axios.post('/api/v1/semester/edit/'+this.semseterId ,{
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
              saveSemester(){
                axios.post('/api/v1/create/semester',{
                  en_name:this.EnSemName,
                  ar_name:this.ArSemName
                }).then(res=>{
                  
        this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Added Succesfully',
            duration: 9000,
            dismissible: true
          });
             this.reload()
                })
              },

   reload(){
       window.location.reload()
   }
      
            
        },
        
        mounted(){
            this.getSemesters()
            
        }
    }
</script>