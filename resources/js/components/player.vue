
<template>

<div class="row ">  
    
    <div class="player-video">
    <video-player  class="video-player-box"
                 ref="videoPlayer"
                 :options="playerOptions"
                 :playsinline="true"
                 customEventName="customstatechangedeventname"
                 @play="onPlayerPlay($event)"
                 @pause="onPlayerPause($event)"
                 @ended="onPlayerEnded($event)"
                 @waiting="onPlayerWaiting($event)"
                 @playing="onPlayerPlaying($event)"
                 @loadeddata="onPlayerLoadeddata($event)"
                 @timeupdate="onPlayerTimeupdate($event)"
                 @canplay="onPlayerCanplay($event)"
                 @canplaythrough="onPlayerCanplaythrough($event)"
 
                 @statechanged="playerStateChanged($event)"
                 @ready="playerReadied">
  </video-player>
</div>
<div  class="col-md-4 contenttttt">
     <div class="accordion" id="accordionExample">
  <div v-for="course in courseData.chapters" :key="course.id" class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         {{course.title}}
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div  class="card-body">
         <ul>
             <li v-for="lesson in course.lessons" :key="lesson.id">
                 {{lesson.title}}

             </li>
         </ul>
      </div>
    </div>
  </div>
  
</div>

</div>
</div>

</template>
 
<script>
  // Similarly, you can also introduce the plugin resource pack you want to use within the component
  // import 'some-videojs-plugin'
  import 'video.js/dist/video-js.css'
  import axios from '../axios'
 
import { videoPlayer } from 'vue-video-player'
  export default {
    data() {
      return {
        playerOptions: {
          // videojs options
          muted: true,
          language: 'en',
          playbackRates: [0.7, 1.0, 1.5, 2.0],
          sources: [{
            type: "video/mp4",
            // src: "https://cdn.theguardian.tv/webM/2015/07/20/150716YesMen_synd_768k_vp8.webm"
            src:''
          }],
          poster: "/static/images/author.jpg",
        },
        courseData:[]
      }
    },
     components: {
    videoPlayer
  },
    mounted() {
        this.getData()
    //   console.log('this is current player instance object', this.player)


    },
    computed: {
      player() {
        return this.$refs.videoPlayer.player
      }
    },
    methods: {
        getData() {
             axios.post('/api/v1/single-lesson',{lesson:'lessonwithfiles'})
          .then(res => {

             this.courseData = res.data.result
             this.playerOptions.sources[0].src = this.courseData.lesson.media_video.url
               console.log("info",res)

          }).catch(err => {
          console.log(err)
      })

     
},
  
      // listen event
      onPlayerPlay(player) {
        // console.log('player play!', player)
      },
      onPlayerPause(player) {
        // console.log('player pause!', player)
      },
      // ...player event
 
      // or listen state event
      playerStateChanged(playerCurrentState) {
        // console.log('player current update state', playerCurrentState)
      },
 
      // player is ready
      playerReadied(player) {
        console.log('the player is readied', player)
        // you can use it to do something...
        // player.[methods]
      }
    }
  }
</script>
<style >

.content-intro {
  padding: 4%;
  border-bottom: 1px solid #e2dddd;
}
.player-video{
        margin-top: 8%;
    margin-left: 3%;
}
.contenttttt{
   margin-top: 8%;
    /* margin-left: 6%; */
}
</style>