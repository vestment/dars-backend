<template>
  <div id="app">
    <div class="container ">
      <div class="row ">

        <div class="col-lg-8">
          <router-view></router-view>
        </div>
        <div class="col-md-4 ">
          <div class="accordion" id="accordionExample">

            <div v-for="chapter in courseData.course_timeline" :key="chapter.id" class="card shadow mb-3">
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

                  <table class="table table-bordered">
                    <tbody>
                    <tr v-for="lesson in chapter.lessons" :key="lesson.model.id">
                      <td>
                        <router-link :to="{name:'player',params:{slug:lesson.model.slug}}">{{ lesson.model.title }}
                        </router-link>
                        <p class="m-0"><small class="text-sm" v-if="lesson.model.media_video">
                          <i class="fa fa-play-circle"></i>
                          {{lesson.model.media_video.duration}}
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
                           data-target="#notesModal"><i class="far fa-sticky-note mr-4 pr-3"></i></a>

                      </td>

                    </tr>
                    <tr v-if="chapter.tests.length > 0" v-for="test in chapter.tests" :key="test.id">

                      <td>
                        <router-link :to="{name:'test',params:{slug:test.model.slug}}">{{
                          test.model.title
                          }}
                        </router-link>
                      </td>
                      <td><i class="fas fa-laptop"></i></td>
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
    },
  },
  mounted() {
    console.log(this)
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
