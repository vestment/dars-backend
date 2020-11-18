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
                                        <h3 >Education System</h3>
                                        <p  class="page_subtitle">Dashboard 
                                            <span  class="page_subtitle_inenr active_subcat">
                                                <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="8px" height="10px">
                                                    <path  fill-rule="evenodd" fill="rgb(254, 23, 36)" d="M-0.000,10.000 L-0.000,-0.000 L8.000,5.000 L-0.000,10.000 Z">
                                                    </path>
                                                </svg> Education system
                                           
                                           </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div  class="container-fluid plr_30" v-if="errors.length">
                        <div  class="row justify-content-center">
                             <div  class="col-lg-12">
                                 <div class="alert alert-danger" role="alert" v-for="error in errors">
                                    {{error}}
                                </div>
                             </div>    
                        </div>
                    </div>
                    <div  class="container-fluid plr_30">
                        <div  class="row justify-content-center">
                            <div  class="col-lg-12">
                                <div  class="white_box2">
                                    <div  class="white_box_tittle list_header">
                                        <h4 >Countries and Education systems</h4> 
                                        <div  class="box_right d-flex lms_block">
                                           
                                            <div  class="add_button ml-10">
                                                <a  href="#" data-toggle="modal" data-target="#saveCat" class="btn_1">Add Country</a>
                                                <a  href="#" data-toggle="modal" data-target="#saveSubCat" class="btn_1">Add Education System</a>
                                                <router-link class="btn_1" to="/user/semesters" >All Semesters</router-link>
                                                <router-link class="btn_1" to="/user/years" >All Years</router-link>

                                            </div>
                                        </div>
                                    </div> 
                                    <div  class="row">
                                        <div v-for="countryObj in countries" :key="countryObj.id" class="col-xl-6">
                                            <div  class="lms_category_wrap">
                                                <div  class="category_header lms_block d-flex justify-content-between align-items-center">
                                                    <div  class="category_left">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <img :src="countryObj.image" alt="" style="    width: 62px;
    height: 64px;">
                                                                <!-- <p>{{countryObj.image}}</p> -->
                                                            </div>
                                                            <div class="col-md-8">
                                                                <h4 >{{countryObj.en_name}}</h4> 
                                                                <p >Total {{countryObj.edu_systems.length}}  Education Systems</p>
                                                            </div>

                                                        </div>
                                                    </div> 
                                                    <div  class="category_right">
                                                        <a href="#" v-on:click="showCountry(countryObj.id)" data-toggle="modal" data-target="#editCat" class="white_btn">Edit</a> 
                                                        <a  href="#" v-on:click="DeleteCountry(countryObj.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 
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
                                                            <a  href="#" data-toggle="modal" data-target="#edit_subCat" v-on:click="ShowEduSys(eduSys.id)" class="white_btn">Edit</a>
                                                            <a v-on:click="openModal(eduSys.id)" data-placement="top" title="Add Education Stage" href="#" data-toggle="modal" data-target="#add_edu_stage" class="white_btn"> 
                                                                <i  class="fas fa-plus-circle"></i>
                                                            </a>
                                                        <a  href="#" v-on:click="DeleteEduSys(eduSys.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 


                                                           
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>

                                                                                
                                                                                
                                    </div>
                                </div>
                            </div> 
                           
                        </div> 
                        <div  id="saveCat" tabindex="-1" class="modal fade medium_modal_width cs_modal" aria-hidden="true" style="display: none;">
                            <div  role="document" class="modal-dialog modal-dialog-centered">
                                <div  class="modal-content">
                                    <form  enctype="multipart/form-data" novalidate="true">
                                        <div  class="modal-header">
                                            <h5 >Add Country</h5>
                                            
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form-row">
                                                <div  class="col-12">
                                                    <div  class="input_wrap">
                                                        <label >Arabic Country Name</label> 
                                                        <input v-model="ar_name" placeholder="Country Name" class="input_form form-control" required> 
                                                        <label >English Country Name</label> 
                                                        <input v-model="en_name" placeholder="Country Name" class="input_form form-control">
                                                        <label >Key</label> 
                                                        <input v-model="key" placeholder="Country Key" class="input_form form-control">
                                                        
                                                                                  </div> 
                                                            <input type="file" name="picture" class="form-control-file" id="picture" @change="onFileChange">

                                                </div> 
                                             
                                              
                                        </div>
                                       </div> 
                                        <div  class="modal-footer modal_btn">
                                                <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2"> Cancel </button> 
                                                <button  @click="createCountry" data-dismiss="modal" aria-label="Close" class="btn_1 m-0" type="submit">Add</button>
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
                                                <button  v-on:click="saveEduSystem" data-dismiss="modal" aria-label="Close" type="submit" class="btn_1 m-0">Add</button>
                                        </div>
                                     </div>
                                   </form>
                               </div>
                            </div>
                        </div> 
                         <div   id="editCat" tabindex="-1" class="modal fade medium_modal_width cs_modal" style="display: none;" aria-hidden="true">
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
                                                        <input v-model="ar_name" placeholder="Country Name" class="input_form form-control"> 
                                                         <label >English Country Name</label> 
                                                        <input v-model="en_name" placeholder="Country Name" class="input_form form-control"> 
                                                         <label >Key</label> 
                                                        <input v-model="key" placeholder="Key" class="input_form form-control"> <!---->
                                                        <input type="file" name="picture" class="form-control-file" id="picture" @change="onFileChange">

                                                    </div>
                                                </div> 
                                           </div>
                                      </div> 
                                      <div  class="modal-footer modal_btn">
                                          <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel </button>
                                          <button  v-on:click="updateCountry" class="btn_1 m-0"  data-dismiss="modal" aria-label="Close" type="submit">Update Country</button>
                                     </div>
                                  </form>
                              </div>
                           </div>
                        </div> 
                        <div  id="edit_subCat" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                 <form  >
                                            <div  class="modal-body">
                                                <div  class="input_wrap">
                                                   <div  class="input_wrap">
                                                        <label  >Select Country</label> 
                                                        <div  class="Select_50px height_51">
                                                            <select v-model="Showcountry_id" class="form-control">
                                                                <option v-for="country in countries" :key="country.id" :value="country.id"  > {{country.en_name}} </option>
                                                                 
                                                            </select> <!---->
                                                        </div>
                                                   </div>
                                                    <label>English Education System Name</label> 
                                                    <input  v-model="ShowEneduSysName" class="input_form form-control">
                                                    <label>Arabic Education System Name</label> 
                                                    <input  v-model="ShowAreduSysName" class="input_form form-control">

                                                </div> 
                                                     
                                            </div> 
                                                   
                                         
                                            <div  class="modal-footer modal_btn">
                                               <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2">Cancel</button>
                                               <button  v-on:click="UpdateEduSys" data-dismiss="modal" aria-label="Close"  type="submit" class="btn_1 m-0">Update</button>
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
                                            <div class="alert alert-danger" role="alert" v-for="error in err">
                                                        {{error}}
                                            </div>
                                            <div  class="form-row">
                                                <div  class="col-6">
                                                    <div  class="input_wrap">
                                                        <div v-for="eduStage in eduStages" :key="eduStage.id" class="card">
                                                            <div class="card-body row">
                                                                <p class="col-md-4">{{eduStage.en_name}}</p>
                                                                 <div  class="category_option col-md-8">
                                                                    <!-- <span  class="status_btn">Active</span>  -->

                                                                    <a v-on:click="ShowEduStage(eduStage.id)" href="#" data-toggle="modal" data-target="#edit_edu_stage"  class="white_btn">Edit</a>
                                                                    <a v-on:click="openSemModal(eduStage.id)" data-placement="top" title="Add Semester" href="#" data-toggle="modal" data-target="#add_semester" class="white_btn"> 
                                                                        <i  class="fas fa-plus-circle"></i>
                                                                    </a>
                                                                   <a  href="#" v-on:click="DeleteEduStage(eduStage.id)" class="red_btn"><i class="far fa-trash-alt"></i></a> 


                                                                </div>
                                                                <span style="cursor:pointer" v-for="sem in eduStage.semesters " :key="sem.id" class="badge badge-pill badge-danger mt-4 ml-3" v-on:click="removeSemestersFromStage(eduStage.id,sem.id)" data-placement="top" title="Delete This Semester" href="#" data-toggle="modal" data-target="#add_edu_stage">{{sem.en_name}}</span>

                                                                 
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

    <div id="edit_edu_stage" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade medium_modal_width cs_modal">
                           <div  role="document" class="modal-dialog modal-dialog-centered">
                              <div  class="modal-content">
                                     <form >
                                        <div  class="modal-header">
                                            <h5 >Edit Education Stage</h5>
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form-row">
                                               
                                                <div  class="col-12">
                                                      <!-- <div  class="input_wrap">
                                                        <label  >Select Country</label> 
                                                        <div  class="Select_50px height_51">
                                                            <select v-model="ShowEduSysId" class="form-control">
                                                                <option v-for="country in countries" :key="country.id" :value="country.id"  > {{country.en_name}} </option>
                                                                 
                                                            </select>
                                                        </div>
                                                   </div> -->
                                                    <div  class="input_wrap">
                                                        <label >Arabic Education Stage Name </label> 
                                                        <input v-model="ShowArStageName" placeholder="Education Stage Name" class="input_form form-control"> 
                                                        <label >English Education Stage Name </label> 
                                                        <input v-model="ShowEnStageName" placeholder="Education Stage Name" class="input_form form-control">
                                                       
                                                    </div>
                                                    <button  v-on:click="UpdateEduStage" aria-label="Close" data-dismiss="modal" class="btn_1 m-0" >Update</button>
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
                                     <form >
                                        <div  class="modal-header">
                                            <h5 >Add Semester</h5>
                                        </div> 
                                        <div  class="modal-body">
                                            <div  class="form">
                                                
                                                <div  class="col-12">
                                                    <div  class="input_wrap">
                                                        <label  >Select Semesters</label> 
                                                         <multiselect  v-model="semesters" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="en_name" track-by="id" :closeOnSelect="false" :options="AllSemesters" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                                                   </div>
                                                  
                                                </div> 
                                             
                                              
                                        </div>
                                       </div> 
                                        <div  class="modal-footer modal_btn">

                                                <button  type="button" data-dismiss="modal" aria-label="Close" class="close white_btn2"> Close </button> 
                                                <button  v-on:click="AssignSmesters" data-dismiss="modal" aria-label="Close" class="btn_1 m-0" type="submit">Add</button>
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
import axios from '../../axios'
import VueSimpleAlert from "vue-simple-alert";
import Multiselect from 'vue-multiselect'


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
            Showcountry_id:'',
            ShowAreduSysName:'',
            ShowEneduSysName:'',
            ShowArStageName:'',
            ShowEnStageName:'',
            ArStageName:'',
            EnStageName:'',
            eduSysId:'',
            ArSemesterName:'',
            EnSemesterName:'',
            eduStageId:'',
            countryId:'',
            edu_system_id:'',
            countries:[],
            eduStages:[],
            semesters:[],
              errors: [],
               err: [],
            AllSemesters:[],
            approvers:[],
            semestersid:[],
            stageModal: false,
            smesterModal:false,
            showCountryModal:false,
            showEduSysModal:false,
            file: '',
            selectedFile:''
    


        
    }
},
components: { Multiselect },
methods:{

      addTag (newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
      }
      this.options.push(tag)
      this.value.push(tag)
    },
     openSemModal(id){
       this.smesterModal = true
    //    this.stageModal = false
       this.eduStageId = id

   },
   showCountry(id){
       this.countryId = id
       this.showCountryModal = true
       axios.get('/api/v1/country/'+this.countryId).then(res => {
           
           this.ar_name = res.data.data.ar_name
           this.en_name = res.data.data.en_name
           this.key = res.data.data.key
       })

   },
   onFileChange(event){
      
    this.file = event.target.files[0];
},
    createCountry(){
       

        if (!this.ar_name) {
        this.errors.push("Arabic Name is Required in Add Country.");
        
      }if(!this.en_name){
        this.errors.push("English Name is Required in Add Country.");
      }
      if(!this.key){
        this.errors.push("key is Required in Add Country.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{
         
       let formData = new FormData();

        formData.append("image", this.file);
        formData.append("ar_name", this.ar_name);
        formData.append("en_name", this.en_name);
        formData.append("key", this.key);
        axios.post('/api/v1/create-country',  formData ).then(res => {
             if (res.data.success == true) {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Country Added Succesfully',
            duration: 9000,
            dismissible: true
          });
           this.getCountries()
        }else{
  this.$toast.open({
            type: 'success',
            position: 'top-right',
            message:err.response ,
            duration: 9000,
            dismissible: true
          });
        }
        // this.reload()
      }).catch(err => {
        console.log(err.response)
      
      })
      }
    },
   getCountries(){
        axios.get('/api/v1/get-countries').then(res => {
            this.countries = res.data.data
            console.log (this.countries)
            
        })

   },
   saveEduSystem(){
        if (!this.country_id) {
        this.errors.push("country is Required in Add Education System.");
        
      }if(!this.AreduSysName){
        this.errors.push("Arabic Name is Required in Education System.");
      }
      if(!this.EneduSysName){
        this.errors.push("English Name is Required in Education System.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{
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
         this.getCountries()
        }
      }).catch(err => {
        console.log(err.response)
      })
      }
   },
   openModal(id){
       this.stageModal =true
       this.eduSysId = id
       this.getEduStages()

   },
   saveEduStage(){
    if(!this.ArStageName){
        this.err.push("Arabic Name is Required in Add Education Stage.");
      }
      if(!this.EnStageName){
        this.err.push("English Name is Required in Add Education Stage.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{
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
      }
   },
   getEduStages(){
       axios.get('/api/v1/edu-stage/'+this.eduSysId).then( res => {
           this.eduStages = res.data.data
          
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


        if (!this.ar_name) {
        this.errors.push("Arabic Name is Required in Edit Country.");
        
      }if(!this.en_name){
        this.errors.push("English Name is Required in Edit Country.");
      }
      if(!this.key){
        this.errors.push("key is Required in Edit Country.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{

         let formData = new FormData();

    formData.append("image", this.file);
    formData.append("ar_name", this.ar_name);
    formData.append("en_name", this.en_name);
    formData.append("key", this.key);
       axios.post('/api/v1/edit/country/'+this.countryId,formData).then(res =>{

             if (res.data.success == true) {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Country Updated Succesfully',
            duration: 9000,
            dismissible: true
          });
         
        }
        // document.getElementsByClassName('modal-dialog').style.display = 'none'
        // this.showCountryModal = false
this.getCountries()
       })
      }

   },
   DeleteCountry(id){

this.$confirm("Are you sure?").then(() => {
    
    axios.delete('/api/v1/remove/country/'+id).then(res=>{

         this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Country Deleted Succesfully',
            duration: 9000,
            dismissible: true
          });

        this.reload()

    })
});
           
   },
   ShowEduSys(id){
       this.showEduSysModal = true
       this.EduSysId = id
       axios.get('/api/v1/edu-system/show/'+id).then(res=>{
           this.ShowAreduSysName = res.data.data.ar_name
           this.ShowEneduSysName = res.data.data.en_name
           this.Showcountry_id = res.data.data.country_id
       })
           

   },
   
   DeleteEduSys(id){
       

this.$confirm("Are you sure?").then(() => {
    
    axios.delete('/api/v1/edu-system/remove/'+id).then(res=>{

         this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Education System Deleted Succesfully',
            duration: 9000,
            dismissible: true
          });

        this.reload()

    })
});
   },
   UpdateEduSys(){

       if (!this.Showcountry_id) {
        this.errors.push("country is Required in Edit Education System.");
        
      }if(!this.ShowAreduSysName){
        this.errors.push("Arabic Name is Required in Edit Education System.");
      }
      if(!this.ShowEneduSysName){
        this.errors.push("English Name is Required in Edit Education System.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{
       axios.post('/api/v1/edu-system/edit/'+this.EduSysId ,{
           ar_name:this.ShowAreduSysName,
           en_name:this.ShowEneduSysName,
           country_id:this.Showcountry_id
       }).then(res=>{

           this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Updated Succesfully',
            duration: 9000,
            dismissible: true
          });

          this.getCountries()
           
       })
     
      }

   },
   DeleteEduStage(id){


this.$confirm("Are you sure?").then(() => {
    
    axios.delete('/api/v1/edu-stage/remove/'+id).then(res=>{

         this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Education System Deleted Succesfully',
            duration: 9000,
            dismissible: true
          });

         this.openModal(this.eduSysId)
    })
});

   },
   ShowEduStage(id){
       this.eduStageId = id 
       axios.get('/api/v1/edu-stage/show/'+id).then(res=>{
          
           this.ShowEnStageName= res.data.data.en_name
           this.ShowArStageName= res.data.data.ar_name
           this.edu_system_id  = res.data.data.edu_system_id

       })

   },
   UpdateEduStage(){
      
    if(!this.ShowArStageName){
        this.err.push("Arabic Name is Required in Edit Education Stage.");
      }
      if(!this.ShowEnStageName){
        this.err.push("English Name is Required in Edit Education Stage.");
      }
    //   if(!this.image){
    //     this.errors.push("image  is Required in Add Country.");
    //   }
      else{

       axios.post('/api/v1/edu-stage/edit/'+this.eduStageId,{

           ar_name:this.ShowArStageName,
           en_name:this.ShowEnStageName,
           edu_system_id:this.edu_system_id

       }).then(res=>{
           this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Updated Succesfully',
            duration: 9000,
            dismissible: true
          });
          this.openModal(this.edu_system_id)
       })


      }
   },
   getSemesters(){
      
      axios.get('/api/v1/get-semesters').then(res=>{
          this.AllSemesters = res.data
      })
   },
        
   AssignSmesters(){
       
       
        if(!this.semesters){
        this.err.push("Semester Name is Required in Add semesters.");
      }
      else{
           this.semesters.forEach((semester) => {
                     this.semestersid.push(semester.id);
                });
       axios.post('/api/v1/edu-stage/semesters',{
           semesters:this.semestersid,
           edu_stage_id:this.eduStageId
       })

       this.getEduStages()
      }
   },

   reload(){
      this.getCountries()
   },

 removeSemestersFromStage(statgeID,SemID){

     axios.post('/api/v1/edu-stage/semesters/remove',{
         edu_stage_id:statgeID,
         semesters:[SemID]
     }).then(res=>{

 this.getEduStages()

     })


 }  
          
        
      
 

   
   

   
},
mounted(){
    this.getCountries()
    this.getSemesters()
}
}
</script>