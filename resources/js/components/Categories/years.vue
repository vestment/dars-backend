<template>
<div>
    <div  class="add_button ml-10 mb-5">
                                                <a  href="#" data-toggle="modal" data-target="#saveYear" class="btn_1">Add Year</a>
                                              
                                            </div>
   <table class="table table-bordered">
  <thead>
    <tr>
      
      <th scope="col">Year</th>
      <th scope="col">Actions</th>


    </tr>
  </thead>
  <tbody>
    <tr v-for="year in years" :key="year.id" >
      <td>{{year.year}}</td>
      
      <td>
             <a  href="#" data-toggle="modal" data-target="#edit_year" v-on:click="show(year.id)" class="white_btn">Edit</a>
             <a  href="#" v-on:click="DeleteYear(year.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 

      </td>
      
    </tr>
 
  </tbody>
</table>
   <div  id="edit_year" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                    
                                                    <label>Year</label> 
                                                    <input  v-model="ShowYear" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="UpdateYear" data-dismiss="modal" aria-label="Close"  type="submit" class="btn_1 m-0">Update</button>
                                            </div>
                                  </form>
                              </div>
                        </div>
                    </div>
                    <div  id="saveYear" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                 
                                                    <label>Year</label> 
                                                    <input  v-model="year" class="input_form form-control">
                                                 

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="saveYear" type="submit" data-dismiss="modal" aria-label="Close"  class="btn_1 m-0">Save</button>
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
                years: [],
                ShowYear:'',
                YearId:'',
                year:''

               
            }
        },
        methods:{
            getData(){
                axios.get('/api/v1/get-years').then(res=>{
                    this.years = res.data
                })
            },
            show(id){
              this.YearId = id
              axios.get('/api/v1/year/'+id).then(res=>{
                console.log(res)
                this.ShowYear = res.data.year
            

              })
            },
            UpdateYear(){
              axios.post('/api/v1/year/edit/'+this.YearId ,{
                year : this.ShowYear,
              

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
            DeleteYear(id){
              this.$confirm("Are you sure?").then(() => {

              axios.delete('/api/v1/year/remove/'+id).then(res=>{

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
              saveYear(){
                axios.post('/api/v1/create/year',{
                  year:this.year,
                
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
            this.getData()
            
        }
    }
</script>