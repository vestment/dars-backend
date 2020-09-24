<template>
  <div id="app">
    <div class=" ">
      <div class="row ">

        <div class="col-lg-8">
          <div class="card">
            <div class="ajax-loader" style="display: none" id='loading-bg'></div>
            <router-view></router-view>
          </div>
        </div>
        <div class="col-lg-4 mt-sm-1">
          <div class="accordion" id="accordionExample">

            <div v-for="chapter in courseData.course_timeline" v-if="chapter.data" :key="chapter.data.id"
                 class="card shadow mb-3">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                          :data-target="'#chapter-'+chapter.data.id" aria-expanded="true" aria-controls="collapseOne">
                    {{ chapter.data.title }}
                  </button>
                </h2>
              </div>
              <div :id="'chapter-'+chapter.data.id" class="collapse show" aria-labelledby="headingOne"
                   data-parent="#accordionExample">
                <div class="card-body">

                  <table class="table table-bordered">
                    <tbody>
                    <tr v-for="lesson in chapter.lessons" :key="lesson.model.id" :id="'lesson-'+lesson.model.id"
                        :class="{'active': slug== lesson.model.slug}">
                      <td>
                        <router-link v-if="lesson.canView" :to="{name:'player',params:{slug:lesson.model.slug}}">
                          <i v-if="lesson.canView" class="fas fa-unlock text-success"></i>
                          <i v-else class="text-danger fa fa-lock"></i> {{ lesson.model.title }}
                        </router-link>
                        <a v-else> <i v-if="lesson.canView" class="fas fa-unlock text-success"></i>
                          <i v-else class="text-danger fa fa-lock"></i> {{ lesson.model.title }}</a>
                        <p class="m-0"><small class="text-sm" v-if="lesson.model.media_video">
                          <i class="fa fa-play-circle"></i>
                          {{ lesson.model.media_video.duration }}
                        </small></p>
                      </td>
                      <td class="">

                        <a v-if="lesson.model.media_audio" target="_blank"
                           :href="'/storage/uploads/'+lesson.model.media_audio.name">
                          <i class="far fa-file-audio"></i>
                        </a>
                        <a v-if="lesson.model.media_p_d_f" target="_blank"
                           :href="'/storage/uploads/'+lesson.model.media_p_d_f.name">
                          <i class="far fa-file-pdf"></i>
                        </a>
                        <a v-if="lesson.model.downloadable_media.length > 0" @click="setDownloadableMedia(lesson.model)"
                           data-toggle="modal"
                           data-target="#downloadableMediaModal" href="#downloadableMediaModal"><i
                            class="fa fa-download"></i></a>
                        <a v-if="lesson.model.notes.length > 0" href="#notesModal"
                           @click="()=> {notes = lesson.model.notes}"
                           data-toggle="modal"
                           data-target="#notesModal"><i class="far fa-sticky-note"></i></a>

                      </td>

                    </tr>
                    <tr v-if="chapter.test && chapter.test.model" :key="chapter.test.model.id">

                      <td>
                        <router-link :to="{name:'test',params:{slug:chapter.test.model.slug}}">
                          <i v-if="chapter.test && chapter.test.model.test_result.length > 0 && (chapter.test.model.test_result[chapter.test.model.test_result.length-1].test_result >= chapter.test.model.min_grade)"
                             class="fas fa-check text-success"></i>
                          <i v-else-if="chapter.test && chapter.test.model.test_result.length == 0"
                             class="text-warning fas fa-hourglass-start"></i>

                          <i v-else class="text-danger fa fa-times"></i>
                          {{ chapter.test.model.title }}
                        </router-link>
                      </td>
                      <td>
                        <i class="fas fa-laptop"></i>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="type == 'player'" class="row">
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
      <div class="modal fade" id="downloadableMediaModal" tabindex="-2"
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
                  <a class="text-center"
                     :href="'/download?filename='+media.name+'&lesson='+downloadableMedia.lesson.id">{{
                      media.name.replace(/\.[^/.]+$/, "")
                    }} <i
                        class="fa fa-download"></i></a>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal fade" id="notesModal" tabindex="-2" aria-labelledby="notesModalLabel"
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
      <div class="modal fade" id="edit-note-modal" tabindex="-1" role="dialog"
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

  </div>
</template>

<script>
import player from './components/player.vue'
import {test} from "./components/test.vue";
import axios from "./axios";

export default {
  name: 'app',
  data() {
    return {
      type: '',
      slug: '',
      courseData: [],
      notes: [],
      newNote: {contentText: ''},
      current_note: {contentText: ''},
      downloadableMedia: {data: '', lesson: ''},
    }
  },
  components: {
    player,
    test
  },
  watch: {
    $route() {
      this.type = this.$route.name
      this.slug = this.$route.params.slug
      this.$forceUpdate();
    },
  },
  mounted() {
    this.slug = this.$route.params.slug
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
  },
}
</script>
