<template>
<div>
    <div  class="add_button ml-10 mb-5">
                                                <a  href="#" data-toggle="modal" data-target="#saveSem" class="btn_1">Add subject</a>
                                              
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
    <tr v-for="subject in subjects" :key="subject.id" >
      <td>{{subject.ar_name}}</td>
      <td>{{subject.en_name}}</td>
      <td>
             <a  href="#" data-toggle="modal" data-target="#edit_subject" v-on:click="Showsubject(subject.id)" class="white_btn">Edit</a>
             <a  href="#" v-on:click="Deletesubject(subject.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 

      </td>
      
    </tr>
 
  </tbody>
</table>
   <div  id="edit_subject" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                    <label>English subject Name</label> 
                                                    <input  v-model="ShowEnSubName" class="input_form form-control">
                                                    <label>Arabic subject Name</label> 
                                                    <input  v-model="ShowArSubName" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="Updatesubject" data-dismiss="modal" aria-label="Close"  type="submit" class="btn_1 m-0">Update</button>
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
                                                 
                                                    <label>English subject Name</label> 
                                                    <input  v-model="EnSubName" class="input_form form-control">
                                                    <label>Arabic subject Name</label> 
                                                    <input  v-model="ArSubName" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="savesubject" type="submit" data-dismiss="modal" aria-label="Close"  class="btn_1 m-0">Save</button>
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
                subjects: [],
                ShowEnSubName:'',
                ShowArSubName:'',
                subjectId:'',
                EnSubName:'',
                ArSubName:''

               
            }
        },
        methods:{
            getsubjects(){
                axios.get('/api/v1/get-subjects').then(res=>{
                    this.subjects = res.data
                })
            },
            Showsubject(id){
              this.subjectId = id
              axios.get('/api/v1/subject/'+id).then(res=>{
                this.ShowEnSubName = res.data.en_name
                this.ShowArSubName = res.data.ar_name

              })
            },
            Updatesubject(){
              axios.post('/api/v1/subject/edit/'+this.subjectId ,{
                en_name : this.ShowEnSubName,
                ar_name : this.ShowArSubName

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
            Deletesubject(id){
              this.$confirm("Are you sure?").then(() => {

              axios.delete('/api/v1/subject/remove/'+id).then(res=>{

                    this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Deleted Succesfully',
            duration: 9000,
            dismissible: true
          });
this.reload()
              })
            }

              )},
              savesubject(){
                axios.post('/api/v1/create/subject',{
                  en_name:this.EnSubName,
                  ar_name:this.ArSubName
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
            this.getsubjects()
            
        }
    }
</script>