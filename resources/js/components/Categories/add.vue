<template>
    <div>
        <div data-v-0681b483="" class="main_content_iner">
            <div  data-v-0681b483="">
                <div  class="main_content_iner">
                    <div  class="container-fluid no-gutters">
                        <div  class="row">
                            <div  class="col-lg-12 p-0">
                                <div  class="header_iner d-flex justify-content-between align-items-center">
                                    <div  class="sidebar_icon d-lg-none"><i  class="ti-menu"></i>
                                    </div>
                                    <div  class="page_tittle">
                                        <h3 >Category</h3>
                                        <p  class="page_subtitle">Dashboard 
                                            <span  class="page_subtitle_inenr active_subcat">
                                                <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="8px" height="10px">
                                                    <path  fill-rule="evenodd" fill="rgb(254, 23, 36)" d="M-0.000,10.000 L-0.000,-0.000 L8.000,5.000 L-0.000,10.000 Z">
                                                    </path>
                                                </svg>  Category List
                                           
                                           </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div  class="container-fluid plr_30">
                        <div  class="row justify-content-center">
                            <div  class="col-lg-12">
                                <div  class="white_box2">
                                    <div  class="white_box_tittle list_header">
                                        <h4 >Category List</h4> 
                                        <div  class="box_right d-flex lms_block">
                                            <div  class="serach_field_2">
                                                <div  class="search_inner">
                                                    <form>
                                                        <div  class="search_field">
                                                            <input  type="text" placeholder="Search Category Here">
                                                        </div> 
                                                        <button >
                                                            <i  class="ti-search"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div> 
                                            <div  class="add_button ml-10">
                                                <a  href="#" data-toggle="modal" data-target="#saveCat" class="btn_1">Add Country</a>
                                                <a  href="#" data-toggle="modal" data-target="#saveSubCat" class="btn_1">Add Education System</a>
                                            </div>
                                        </div>
                                    </div> 
                                    <div  class="row">


                                        <div v-for="countryObj in countries" :key="countryObj.id" class="col-xl-6">
                                            <div  class="lms_category_wrap">
                                                <div  class="category_header lms_block d-flex justify-content-between align-items-center">
                                                    <div  class="category_left">
                                                        <h4 >{{countryObj.en_name}}</h4> 
                                                        <p >Total 5 Sub Categories</p>
                                                    </div> 
                                                    <div  class="category_right">
                                                        <a href="#" v-on:click="showCountry(countryObj.id)" data-toggle="modal" data-target="#editCat" class="white_btn">Edit</a> 
                                                        <a  href="#" v-on:click="confirmDelete(countryObj.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 
                                                        <!-- <a  href="#" class="red_btn" style="display: none;">Enable</a> -->
                                                    </div>
                                                </div> 
                                                <ul >
                                                    <li v-for="eduSys in countryObj.edu_systems" :key="eduSys.id">
                                                        <div  class="category_info">
                                                           <p >{{eduSys.en_name}}</p>
                                                        </div> 
                                                        <div  class="category_option">
                                                            <!-- <span  class="status_btn">Active</span>  -->
                                                            <a  href="#" data-toggle="modal" data-target="#edit_subCat" class="white_btn">Edit</a>
                                                            <a v-on:click="openModal(eduSys.id)" data-placement="top" title="Add Education Stage" href="#" data-toggle="modal" data-target="#add_edu_stage" class="white_btn"> 
                                                                <i  class="fas fa-plus-circle"></i>
                                                            </a>

                                                           
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>

                                                                                
                                                                                
                                    </div>
                                </div>
                            </div> 
                            <div  class="col-lg-12">
                                <div  class="lms_pagination_wrap text-center">
                                        <ul>
                                            <li>
                                                <ul  class="pagination"><!---->
                                                    <li class="page-item pagination-page-nav active">
                                                        <a href="#" class="page-link">
                                                        1
                                                             <span class="sr-only">(current)</span>
                                                        </a>
                                                    </li>
                                                    <li class="page-item pagination-page-nav">
                                                        <a href="#" class="page-link">
                                                        2
                                                        <!----></a>
                                                    </li>
                                                    <li class="page-item pagination-next-nav">
                                                        <a href="#" aria-label="Next" class="page-link"><span >
                                                            <i  class="fas fa-caret-right"></i></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </div> 
                        <div  id="saveCat" tabindex="-1" class="modal fade medium_modal_width cs_modal" aria-hidden="true" style="display: none;">
                            <div  role="document" class="modal-dialog modal-dialog-centered">
                                <div  class="modal-content">
                                    <form  >
                                        <div  class="modal-header">
                                            <h5 >Add Country</h5>
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form-row">
                                                <div  class="col-12">
                                                    <div  class="input_wrap">
                                                        <label >Arabic Country Name </label> 
                                                        <input v-model="ar_name" placeholder="Country Name" class="input_form form-control"> 
                                                        <label >English Country Name </label> 
                                                        <input v-model="en_name" placeholder="Country Name" class="input_form form-control">
                                                        <label >Key </label> 
                                                        <input v-model="key" placeholder="Country Key" class="input_form form-control">
                                                    </div>
                                                </div> 
                                             
                                              
                                        </div>
                                       </div> 
                                        <div  class="modal-footer modal_btn">
                                                <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2"> Cancel </button> 
                                                <button  v-on:click="createCountry" class="btn_1 m-0" type="submit">Add</button>
                                        </div>
                                  </form>
                                </div>
                            </div>
                        </div> 
                        <div  id="saveSubCat" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                            <div  role="document" class="modal-dialog modal-dialog-centered">
                                <div  class="modal-content">
                                    <form>
                                        <div  class="modal-header">
                                            <h5 >Add Education System</h5>
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form-row">
                                                <div  class="col-6">
                                                    <div  class="input_wrap">
                                                        <label  >Select Country</label> 
                                                        <div  class="Select_50px height_51">
                                                            <select v-model="country_id" class="form-control">
                                                                <option v-for="country in countries" :key="country.id" :value="country.id"  > {{country.en_name}} </option>
                                                                 
                                                            </select> <!---->
                                                        </div>
                                                   </div>
                                              </div> 
                                              <div  class="col-6">
                                                <div  class="input_wrap">
                                                    <label  >Education System Name In Arabic</label> 
                                                    <input  v-model="AreduSysName" placeholder="Education System Name" class="input_form form-control"> <!---->
                                                </div>
                                               
                                               </div> 
                                                <div  class="col-6">
                                                      <div  class="input_wrap">
                                                    <label  >Education System Name In English</label> 
                                                    <input  v-model="EneduSysName" placeholder="Education System Name" class="input_form form-control"> <!---->
                                                </div>
                                               
                                            </div>
                                        </div> 
                                      
                                        <div  class="modal-footer modal_btn">
                                                <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2"> Cancel  </button> 
                                                <button  v-on:click="saveEduSystem" type="submit" class="btn_1 m-0">Add</button>
                                        </div>
                                     </div>
                                   </form>
                               </div>
                            </div>
                        </div> 
                         <div  v-if="showCountryModal" id="editCat" tabindex="-1" class="modal fade medium_modal_width cs_modal" style="display: none;" aria-hidden="true">
                            <div  role="document" class="modal-dialog modal-dialog-centered">
                                <div  class="modal-content">
                                    <form >
                                        <div  class="modal-header">
                                            <h5 >Update Country</h5>
                                         </div> 
                                        <div  class="modal-body">
                                            <div  class="form-row">
                                                <div  class="col-12">
                                                    <div  class="input_wrap">
                                                        
                                                        <label >Arabic Country Name</label> 
                                                        <input v-model="ar_name" placeholder="Category Name" class="input_form form-control"> 
                                                         <label >English Country Name</label> 
                                                        <input v-model="en_name" placeholder="Category Name" class="input_form form-control"> 
                                                         <label >Key</label> 
                                                        <input v-model="key" placeholder="Key" class="input_form form-control"> <!---->
                                                    </div>
                                                </div> 
                                           </div>
                                      </div> 
                                      <div  class="modal-footer modal_btn">
                                          <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel </button>
                                          <button  v-on:click="updateCountry" class="btn_1 m-0">Update Country</button>
                                     </div>
                                  </form>
                              </div>
                           </div>
                        </div> 
                        <div  id="edit_subCat" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                    <input  type="hidden" name="id" value="2"> 
                                                    <label  for="#"></label> 
                                                    <input  name="name" class="input_form form-control"> <!----></div> 
                                                    <div  class="form-row">
                                                        <div  class="col-4">
                                                            <div  class="input_wrap">
                                                                <label  for="#">Status</label> 
                                                                <div  class="Select_50px">
                                                                    <select  name="status" class="form-control">
                                                                        <option  value="1">Active</option> 
                                                                        <option  value="0">Disable</option> <!---->
                                                                        </select>
                                                                 </div>
                                                            </div>
                                                         </div>
                                                         <div  class="col-4">
                                                               <div  class="input_wrap">
                                                                   <label  for="#">Show Home Page</label> 
                                                                   <div  class="Select_50px">
                                                                        <select  name="show_home" class="form-control">
                                                                                <option  value="0">No</option> 
                                                                                <option  value="1">Yes</option> <!---->
                                                                        </select>
                                                                   </div>
                                                              </div>
                                                         </div> 
                                                         <div  class="col-4">
                                                            <div  class="input_wrap">
                                                                <label  for="#">Position Order</label> 
                                                                <div  class="Select_50px">
                                                                    <select  name="position_order" class="form-control">
                                                                        <option  value="0" selected="selected">Select Position Order</option> 
                                                                        <option  value="6"> 6</option>
                                                                        <option  value="8"> 7</option> 
                                                                        <option  value="9"> 9</option> 
                                                                        <option  value="9"> 10</option>
                                                                    </select> <!---->
                                                                </div>
                                                            </div>
                                                         </div>
                                                    </div>
                                            </div>
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  type="submit" class="btn_1 m-0">Submit</button>
                                            </div>
                                  </form>
                              </div>
                        </div>
                    </div>
                       <div  v-if="stageModal" id="add_edu_stage" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                     <form  onsubmit="return false">
                                        <div  class="modal-header">
                                            <h5 >Add Education Stage</h5>
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form-row">
                                                <div  class="col-6">
                                                    <div  class="input_wrap">
                                                        <div v-for="eduStage in eduStages" :key="eduStage.id" class="card">
                                                            <div class="card-body row">
                                                                <p class="col-md-6">{{eduStage.en_name}}</p>
                                                                 <div  class="category_option col-md-6">
                                                                    <!-- <span  class="status_btn">Active</span>  -->
                                                                    <a  href="#" data-toggle="modal" data-target="#edit_semester" class="white_btn">Edit</a>
                                                                    <a v-on:click="openSemModal(eduStage.id)" data-placement="top" title="Add Semester" href="#" data-toggle="modal" data-target="#add_semester" class="white_btn"> 
                                                                        <i  class="fas fa-plus-circle"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div> 
                                                <div  class="col-6">
                                                    <div  class="input_wrap">
                                                        <label >Arabic Education Stage Name </label> 
                                                        <input v-model="ArStageName" placeholder="Education Stage Name" class="input_form form-control"> 
                                                        <label >English Education Stage Name </label> 
                                                        <input v-model="EnStageName" placeholder="Education Stage Name" class="input_form form-control">
                                                       
                                                    </div>
                                                    <button  v-on:click="saveEduStage" class="btn_1 m-0" >Add</button>
                                                </div> 
                                             
                                              
                                        </div>
                                       </div> 
                                        <div  class="modal-footer modal_btn">
                                                <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2"> Close </button> 
                                                <!-- <button  v-on:click="createCountry" class="btn_1 m-0" type="submit">Add</button> -->
                                        </div>
                                  </form>
                              </div>
                        </div>
                    </div>



                    <div v-if="smesterModal" id="add_semester" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                     <form onsubmit="return false">
                                        <div  class="modal-header">
                                            <h5 >Add Semester</h5>
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form">
                                                
                                                <div  class="col-12">
                                                    <div  class="input_wrap">
                                                        <label >Arabic Semester Name </label> 
                                                        <input v-model="ArSemesterName" placeholder="Semester Name" class="input_form form-control"> 
                                                        <label >English Semester Name </label> 
                                                        <input v-model="EnSemesterName" placeholder="Semester Name" class="input_form form-control">
                                                       
                                                    </div>
                                                  
                                                </div> 
                                             
                                              
                                        </div>
                                       </div> 
                                        <div  class="modal-footer modal_btn">
                                                <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2"> Close </button> 
                                                <button  v-on:click="SaveSmester" class="btn_1 m-0" type="submit">Add</button>
                                        </div>
                                  </form>
                              </div>
                        </div>
                    </div>
                 </div>
             </div>
          </div>
      </div>
   </div>
</template>
<script>
import axios from '../../axios'
import VueSimpleAlert from "vue-simple-alert";
import { Dialog } from 'buefy'
import '../lesson.css'
export default {
name:'addCategory',
data(){
    return{
       
            ar_name:"",
            en_name:"",
            key:"",
            country_id:'',
            AreduSysName:'',
            EneduSysName:'',
            ArStageName:'',
            EnStageName:'',
            eduSysId:'',
            ArSemesterName:'',
            EnSemesterName:'',
            eduStageId:'',
            countryId:'',
            countries:[],
            eduStages:[],
            stageModal: false,
            smesterModal:false,
            showCountryModal:false

        
    }
},
methods:{
     openSemModal(id){
       this.smesterModal = true
    //    this.stageModal = false
       this.eduStageId = id

   },
   showCountry(id){
       this.countryId = id
       this.showCountryModal = true
       axios.get('/api/v1/country/'+this.countryId).then(res => {
           
           this.ar_name = res.data.ar_name
           this.en_name = res.data.en_name
           this.key = res.data.key
       })

   },
    createCountry(){
        axios.post('/api/v1/create-country',{
            ar_name:this.ar_name,
            en_name:this.en_name,
            key:this.key
        }).then(res => {
             if (res.data.success == true) {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Country Added Succesfully',
            duration: 9000,
            dismissible: true
          });
        }
      }).catch(err => {
        console.log(err.response)
      })
    },
   getCountries(){
        axios.get('/api/v1/get-countries').then(res => {
            this.countries = res.data
            console.log(this.countries)
        })

   },
   saveEduSystem(){
      axios.post('/api/v1/create/edu-system',{
            country_id:this.country_id,
            ar_name:this.AreduSysName,
            en_name:this.EneduSysName,
           
        }).then(res => {
             if (res.data.success == true) {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Education System Added Succesfully',
            duration: 9000,
            dismissible: true
          });
          this.$router.go(0)
        }
      }).catch(err => {
        console.log(err.response)
      })
   },
   openModal(id){
       this.stageModal =true
       this.eduSysId = id
       this.getEduStages()

   },
   saveEduStage(){
       axios.post('/api/v1/create/edu-stage',{
           ar_name:this.ArStageName,
           en_name:this.EnStageName,
           edu_system_id:this.eduSysId
       }).then(res => {
            if (res.data.success == true) {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Education System Added Succesfully',
            duration: 9000,
            dismissible: true
          });
         
        }
   
        this.openModal(this.eduSysId)

       }).catch(err => {
        console.log(err.response)
      })
   },
   getEduStages(){
       axios.get('/api/v1/edu-stage/'+this.eduSysId).then( res => {
           this.eduStages = res.data
       })
   },
  
   SaveSmester(){
       axios.post('/api/v1/create/semester',{
           ar_name:this.ArSemesterName,
           en_name:this.EnSemesterName,
           edu_stage_id:this.eduStageId
       })

   },
   updateCountry(){
       axios.post('/api/v1/edit/country/'+this.countryId,{
           ar_name:this.ar_name,
           en_name:this.en_name,
           key:this.key
       }).then(res =>{

             if (res.data.success == true) {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Country Updated Succesfully',
            duration: 9000,
            dismissible: true
          });
         
        }

       })

   },
   confirmDelete(id){


                this.$buefy.dialog({
                    title: 'Deleting account',
                    message: 'Are you sure you want to <b>delete</b> your account? This action cannot be undone.',
                    confirmText: 'Delete Account',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => this.$buefy.toast.open('Account deleted!')
                })
            }
        
      
 

   
   

   
},
mounted(){
    this.getCountries()
}
}
</script>