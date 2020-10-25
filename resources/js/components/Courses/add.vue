<template>
    <div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Basic</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Requirements</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Pricing</a>
  </li>
   <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-media-tab" data-toggle="pill" href="#pills-media" role="tab" aria-controls="pills-media" aria-selected="false">Media</a>
  </li>
   <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-finish-tab" data-toggle="pill" href="#pills-finish" role="tab" aria-controls="pills-finish" aria-selected="false">Finish</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
         
         <form>
  <div class="form-group row">
    
        <label  class="col-sm-2 col-form-label">Select Country</label> 
        <div  class="col-sm-10">
            <select v-model="country_id" @change="getEdusys(country_id)"  class="form-control">
                <option v-for="country in countries" :key="country.id" :value="country.id" > {{country.en_name}} </option>
                    
            </select> <!---->
        </div>
                                               
  </div>
    <div class="form-group row">
    
        <label  class="col-sm-2 col-form-label">Select Education System</label> 
        <div  class="col-sm-10">
            <select v-model="country_id" class="form-control">
                <option v-for="country in countries" :key="country.id" :value="country.id"  > {{country.en_name}} </option>
                    
            </select> <!---->
        </div>
                                               
  </div>

 
</form>
  


  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
  <div class="tab-pane fade" id="pills-media" role="tabpanel" aria-labelledby="pills-media-tab">...</div>
  <div class="tab-pane fade" id="pills-finish" role="tabpanel" aria-labelledby="pills-finish-tab">...</div>


</div>
    </div>
</template>
<script>
import axios from '../../axios'
import VueSimpleAlert from "vue-simple-alert";
import Multiselect from 'vue-multiselect'
import '../lesson.css'
export default {
    data(){
        return{
            country_id:'',
            countries:[]
        }
    },
    methods:{
          getCountries(){
        axios.get('/api/v1/get-countries').then(res => {
            this.countries = res.data
            
        })

   },
   getEdusys(id){

   }

    },
    mounted(){
        this.getCountries()

    },
}
</script>