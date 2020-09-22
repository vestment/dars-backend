<template>

  <div v-else class="player-video">
    <video-player class="video-player-box"
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
</template>


<script>
// Similarly, you can also introduce the plugin resource pack you want to use within the component
// import 'some-videojs-plugin'
import 'video.js/dist/video-js.css'
import axios from '../axios'
import {videoPlayer} from 'vue-video-player'
import 'vueditor/dist/style/vueditor.min.css'
import test from './test'
import './lesson.css'

export default {
  name: 'player',
  // props: ['type','slug','data'],
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
          src: ''
        }],
        poster: "/static/images/author.jpg",
      },
      courseData: [],
      notes: [],
      newNote: {contentText: ''},
      current_note: {contentText: ''},
      downloadableMedia: {data: '', lesson: ''},
      slug: this.$route.params.slug ? this.$route.params.slug : this.slug,
    }
  },

  components: {
    videoPlayer,
  },
  watch: {
    $route() {
      this.slug = this.$route.params.slug
    },
    slug() {
      this.getData(this.slug)
    }
  },
  created() {
    console.log(this.slug)
    this.getData(this.slug)
  },
  computed: {
    player() {
      return this.$refs.videoPlayer.player
    }
  },
  methods: {
    // setDownloadableMedia(lesson) {
    //   this.downloadableMedia.data = lesson.downloadable_media;
    //   this.downloadableMedia.lesson = lesson;
    // },
    // setEditorContent(note) {
    //   $('#notesModal').modal('hide');
    //   this.current_note = note;
    //   let editor = this.$refs.noteEdit;
    //   editor.setContent(this.current_note.contentText);
    // },
    // saveNote() {
    //   let editor = this.$refs.noteEdit;
    //   this.current_note.contentText = editor.getContent();
    //   axios.post('/api/v1/save-note', this.current_note)
    //       .then(res => {
    //         if (res.data.status == 'success') {
    //           $('#edit-note-modal').modal('hide');
    //           $('#notesModal').modal('show');
    //         }
    //       }).catch(err => {
    //     console.log(err)
    //   })
    // },
    // addNewNote() {
    //   let editor = this.$refs.newNote;
    //   this.newNote.contentText = editor.getContent();
    //   this.newNote.lesson_id = this.courseData.lesson.id
    //   axios.post('/api/v1/add-note', this.newNote)
    //       .then(res => {
    //         if (res.data.status == 'success') {
    //           editor.setContent('')
    //           this.notes.push(res.data.note);
    //         }
    //       }).catch(err => {
    //     console.log(err)
    //   })
    // },
    // removeNote(id) {
    //   if (confirm('Are you sure you want to delete this note?')) {
    //     axios.post('/api/v1/remove-note', {id: id})
    //         .then(res => {
    //           if (res.data.status == 'success') {
    //             this.notes.splice(this.notes.findIndex(note => note.id === id), 1)
    //           }
    //         }).catch(err => {
    //       console.log(err)
    //     })
    //   }
    // },
    getData(slug) {
      axios.post('/api/v1/single-lesson', {lesson: slug})
          .then(res => {
            if (res.data.result) {
              this.courseData = res.data.result
              this.$parent.courseData = this.courseData
              this.playerOptions.sources[0].src = this.courseData.lesson.media_video.url
              console.log("Lesson", res)
              $('.course-title-header').text(this.courseData.course.title)
              $('.close-lesson').attr('href', this.courseData.course_page)
              $('.course-progress').text(this.courseData.course_progress + ' %')
              $('.progress-bar').css('width', this.courseData.course_progress + '%')
            }
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
      let myPluginCollection = document.getElementsByClassName('svg-embedded')
      if (myPluginCollection) {
        player.el_.appendChild(myPluginCollection[0]);
        setInterval(function () {
          var $div = $('.svg-embedded'),
              docHeight = $div.parent().height(),
              docWidth = $div.parent().width(),
              divHeight = $div.height(),
              divWidth = $div.width(),
              heightMax = docHeight - divHeight,
              widthMax = docWidth - divWidth;
          $div.show();
          $div.css({
            left: Math.floor(Math.random() * widthMax),
            top: Math.floor(Math.random() * heightMax)
          });
        }, 2 * 1000);
      }
    }
  }
}
</script>
<style>


.video-js {
  width: 100%;
}
</style>
