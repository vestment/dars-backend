<template>
  <div class="container ">
    <div class="row ">
      <div class="col-lg-9">
        <div v-if="type=='test'">
          <test/>

        </div>
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

      </div>
      <div class="col-md-3 ">
        <div class="accordion" id="accordionExample">
          <div v-for="chapter in courseData.chapters" :key="chapter.id" class="card shadow mb-3">
            <div class="card-header" id="headingOne">
              <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                        :data-target="'#chapter-'+chapter.id" aria-expanded="true" aria-controls="collapseOne">
                  {{ chapter.title }}
                </button>
              </h2>
            </div>
            <div :id="'chapter-'+chapter.id" class="collapse show" aria-labelledby="headingOne"
                 data-parent="#accordionExample">
              <div class="card-body">
                <ul class="list-group">
                  <li class="list-group-item " v-for="lesson in chapter.lessons" :key="lesson.id">
                    <p class="p-0 m-0"><a :href="'./'+lesson.slug">{{ lesson.title }}</a></p>
                    <p class="play p-0 m-0">
                      <i class="far fa-play-circle "></i>
                      {{ lesson.media_video ? lesson.media_video.duration : 'error getting duration' }}
                    </p>
                    <ul class="float-right list-inline">
                      
                      <li class="list-inline-item" v-if="lesson.notes.length > 0"><a href="#notesModal"
                                                                                     @click="()=> {notes = lesson.notes}"
                                                                                     data-toggle="modal"
                                                                                     data-target="#notesModal"><i
                          class="far fa-sticky-note"></i></a>
                      </li>
                      <li class="list-inline-item" v-if="lesson.media_p_d_f">
                        <a :href="'/storage/uploads/'+lesson.media_p_d_f.name">
                          <i class="far fa-file-pdf"></i>
                        </a>
                      </li>
                      <li class="list-inline-item" v-if="lesson.downloadable_media.length > 0">
                        <a @click="setDownloadableMedia(lesson)" data-toggle="modal"
                           data-target="#downloadableMediaModal" href="#downloadableMediaModal"><i
                            class="fa fa-download"></i></a>
                      </li>
                    </ul>
                  </li>
                  <li class="pt-2 list-group-item" v-if="chapter.test">
                    <a class="text-danger " :href="'./'+chapter.test.slug+'/test'">{{ chapter.test.title }}</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="type !== 'test'" class="row">
      <div class="col-lg-12">
        <div class="card mt-5">
          <div class="card-header">New Note</div>
          <div class="card-body">
            <Vueditor ref="newNote" v-model="newNote.contentText"></Vueditor>
          </div>
          <div class="card-footer">
            <button @click="addNewNote()" type="button" class="btn btn-success">Save</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" v-if="type !== 'test'" id="downloadableMediaModal" tabindex="-2"
         aria-labelledby="downloadableMediaModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="downloadableMediaModalLabel">Lesson Media</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card shadow-c" :data-length="downloadableMedia.length">
              <div v-for="(media,index) in downloadableMedia.data" class="card-body">
                <a class="text-center" :href="'/download?filename='+media.name+'&lesson='+downloadableMedia.lesson.id">{{
                    media.name.replace(/\.[^/.]+$/, "")
                  }} <i
                      class="fa fa-download"></i></a>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="modal fade" v-if="type !== 'test'" id="notesModal" tabindex="-2" aria-labelledby="notesModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="notesModalLabel">Lesson Notes</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="notes-container">
            <div class="card shadow-c" :data-length="notes.length">
              <div v-for="note in notes" :key="note.id" class="card-body">
                <p>{{ note.contentText.replace(/<[^>]*>?/gm, '') }}</p>
                <a class="float-right font-weight-light ml-1 text-white btn btn-primary btn-sm "
                   @click="setEditorContent(note)"
                   data-toggle="modal"
                   data-target="#edit-note-modal"><i
                    class="far fa-edit"></i> </a>
                <a class="float-right btn font-weight-light text-white btn-danger btn-sm"
                   @click="removeNote(note.id)"><i
                    class="fa fa-trash"></i> </a>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="modal fade" v-if="type !== 'test'" id="edit-note-modal" tabindex="-1" role="dialog"
         aria-labelledby="edit-note-modallLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content my-4">
          <div class="modal-header m-3 " style="background: unset;">
            <h5 class="modal-title" id="edit-note-modallLabel">Edit note</h5>
            <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-2">
            <Vueditor ref="noteEdit" v-model="current_note.contentText"></Vueditor>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">Close
            </button>
            <button @click="saveNote()" type="button" class="btn btn-primary">Save changes
            </button>

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
import {videoPlayer} from 'vue-video-player'
import 'vueditor/dist/style/vueditor.min.css'
import test from './test'
import './lesson.css'

export default {
  props: ['slug', 'type'],
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
    }
  },

  components: {
    videoPlayer,
    test
  },

  mounted() {
    this.getData(this.slug)

  },
  computed: {
    player() {
      return this.$refs.videoPlayer.player
    }
  },
  methods: {
    setDownloadableMedia(lesson) {
      this.downloadableMedia.data = lesson.downloadable_media;
      this.downloadableMedia.lesson = lesson;
    },
    setEditorContent(note) {
      $('#notesModal').modal('hide');
      this.current_note = note;
      let editor = this.$refs.noteEdit;
      editor.setContent(this.current_note.contentText);
    },
    saveNote() {
      let editor = this.$refs.noteEdit;
      this.current_note.contentText = editor.getContent();
      axios.post('/api/v1/save-note', this.current_note)
          .then(res => {
            if (res.data.status == 'success') {
              $('#edit-note-modal').modal('hide');
              $('#notesModal').modal('show');
            }
          }).catch(err => {
        console.log(err)
      })
    },
    addNewNote() {
      let editor = this.$refs.newNote;
      this.newNote.contentText = editor.getContent();
      this.newNote.lesson_id = this.courseData.lesson.id
      axios.post('/api/v1/add-note', this.newNote)
          .then(res => {
            if (res.data.status == 'success') {
              editor.setContent('')
              this.notes.push(res.data.note);
            }
          }).catch(err => {
        console.log(err)
      })
    },
    removeNote(id) {
      if (confirm('Are you sure you want to delete this note?')) {
        axios.post('/api/v1/remove-note', {id: id})
            .then(res => {
              if (res.data.status == 'success') {
                this.notes.splice(this.notes.findIndex(note => note.id === id), 1)
              }
            }).catch(err => {
          console.log(err)
        })
      }
    },
    getData(slug) {
      axios.post('/api/v1/single-lesson', {lesson: slug})
          .then(res => {
            if (res.data.result) {
              this.courseData = res.data.result
              this.playerOptions.sources[0].src = this.courseData.lesson.media_video.url
              console.log("info", res)
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
